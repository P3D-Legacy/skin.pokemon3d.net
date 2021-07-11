<?php

namespace App\Http\Controllers\Skin;

use Carbon\Carbon;
use App\Models\Skin;
use ByteUnits\Binary;
use App\Models\GJUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class SkinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $skin = Skin::where('uuid', $uuid)->isPublic()->first();
        abort_unless($skin, 404);
        return view('skin.show')->with('skin', $skin);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newestpublicskins()
    {
        $skins = Skin::isPublic()->orderBy('created_at', 'DESC')->paginate(9);
        return view('skin.public.newest')->with('skins', $skins);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popularpublicskins()
    {
        $skins = Skin::isPublic()->withCount('likers')->orderBy('likers_count', 'desc')->paginate(9);
        return view('skin.public.popular')->with('skins', $skins);
    }

    /**
     * Display a listing of the resource.
     *
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function myskins(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $skins = GJUser::find($gjid)->skins()->get();
        return view('skin.my')->with('skins', $skins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $skincount = GJUser::find($gjid)->skins()->count();
        if($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()->route('skins-my')->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }
        return view('skin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $gju = $request->session()->get('gju');
        $gjau = $request->session()->get('gjau');

        $skincount = GJUser::find($gjid)->skins()->count();

        if($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()->route('skins-my')->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'], // 2MB
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
            'rules' => ['accepted'],
        ]);

        $public = $request->boolean('public');
        $name = $request->get('name');
        
        $skin = Skin::create([
            'owner_id' => $gjid,
            'public' => $public,
            'name' => $name,
        ]);

        $filename = $skin->uuid.'.png';
        $request->file('image')->storeAs(null, $filename, 'skin');

        /*
        *
        * DISCORD WEBHOOK
        *
        */
        if (env('DISCORD_SKIN_UPLOAD_WEBHOOK') && $public) {
            $webhookurl = env('DISCORD_SKIN_UPLOAD_WEBHOOK');
            $json_data = json_encode([
                "content" => $gju." uploaded a new skin for the public to use! Check it out here: ".route('skin-show', $skin->uuid), // Message
                // "username" => env('APP_NAME'), // Username (message posted as username) - NOTE: This should be set in the webhook with the avatar
                "tts" => false, // Enable text-to-speech
                // Embeds Array
                "embeds" => [
                    [
                        "title" => $name, // Embed Title
                        "type" => "rich", // Embed Type
                        "description" => "File size: ".Binary::bytes(Storage::disk('skin')->size($skin->path()))->format(), // Embed Description
                        "url" => route('skin-show', $skin->uuid), // URL of title link
                        "timestamp" => Carbon::now()->toIso8601String(), // Timestamp of embed must be formatted as ISO8601
                        "color" => hexdec("198754"), // Embed left border color in HEX
                        // Footer
                        "footer" => [
                            "text" => $gju, // GJ Username
                            "icon_url" => $gjau, // GJ Avatar
                        ],
                        // Skin URL
                        "thumbnail" => [
                            "url" => $skin->urlPath(),
                        ],
                        // Author
                        "author" => [
                            "name" => $gju.' uploaded a skin', // GJ Username
                        ],
                    ]
                ]
            
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
            $ch = curl_init( $webhookurl );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec( $ch );
            curl_close( $ch );
        }
        /*
        *
        * END DISCORD WEBHOOK
        *
        */

        return redirect()->route('skins-my')->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    /**
     * Apply the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $filename = $gjid.'.png';
        $skin = Skin::where('uuid', $uuid)->first();
        try {
            Storage::disk('player')->put($filename, Storage::disk('skin')->get($skin->path()));
        } catch (FileNotFoundException $e) {
            return redirect()->route('skins-my')->with('warning', 'Could not apply skin.');
        }
        return redirect()->route('home')->with('success', 'Skin was applied! Not seeing it? Refresh the page again.');
    }

    /**
     * Like the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $user = GJUser::find($gjid);
        $skin = Skin::where('uuid', $uuid)->first();
        abort_unless($skin, 404);
        if($user->gjid != $skin->owner_id) {
            $user->toggleLike($skin);
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  str  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $skin = Skin::where('uuid', $uuid)->first();
        if($gjid != $skin->owner_id) {
            return redirect()->route('skins')->with('error', 'You do not own this skin!');
        }
        return view('skin.edit')->with('skin', $skin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $skin = Skin::where('uuid', $uuid)->first();
        if($gjid != $skin->owner_id) {
            return redirect()->route('skins')->with('error', 'You do not own this skin!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:48'],
            'public' => [''],
        ]);
        
        $skin = Skin::where('uuid', $uuid)->first()->update([
            'public' => $request->boolean('public'),
            'name' => $request->get('name'),
        ]);

        return redirect()->route('skins-my')->with('success', 'Skin was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $uuid)
    {
        $gjid = $request->session()->get('gjid');
        $skin = Skin::where('uuid', $uuid)->first();
        if($gjid != $skin->owner_id) {
            return redirect()->route('skins')->with('error', 'You do not own this skin!');
        }
        $filename = $skin->uuid.'.png';
        if(!Storage::disk('skin')->exists($filename)) {
            return redirect()->route('skins')->with('error', 'Skin was not found!');
        }
        Storage::disk('skin')->delete($filename);
        $skin->delete();
        return redirect()->route('skins-my')->with('success', 'Skin was successfully deleted!');
    }
}
