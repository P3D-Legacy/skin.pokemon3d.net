<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Overtrue\LaravelLike\Traits\Likeable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skin extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, Likeable, HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_id',
        'public',
    ];

    // Lets use the uuid for primary key
    // protected $primaryKey = 'uuid';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static $logAttributes = [
        'name',
        'owner_id',
        'public',
    ];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    // Since we are using sessions with gamejolt logins we have to tap the activity to log the causer
    public function tapActivity(Activity $activity)
    {
        $activity->subject_id = $this->id;
        $activity->causer_id = session()->get('gjid');
        $activity->causer_type = GJUser::class;
    }

    /**
     * Get the user that owns the skin.
     */
    public function user()
    {
        return $this->belongsTo(GJUser::class, 'owner_id', 'gjid');
    }

    public function scopeIsPublic($query) {
        return $query->where('public', 1);
    }

    public function path() {
        return $this->uuid.'.png';
    }
    
    public function urlPath() {
        return env('APP_URL').'/skin/'.$this->path();
    }

}
