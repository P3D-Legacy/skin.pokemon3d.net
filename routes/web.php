<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Skin\SkinController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Skin\AuthGJController;
use App\Http\Controllers\Skin\ImportController;
use App\Http\Controllers\Skin\SkinHomeController;
use App\Http\Controllers\Skin\PlayerSkinController;
use App\Http\Controllers\Skin\UploadedSkinController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('skin.'.str_replace(array('http://','https://'), '', env('APP_URL')))->group(function () {
    Route::get('/', [SkinHomeController::class, 'index'])->name('skin-home');
    Route::get('/gj/login', [AuthGJController::class, 'index'])->name('gj-login');
    Route::post('/gj/login', [AuthGJController::class, 'login'])->name('gj-login-post');
    Route::get('/gj/logout', [AuthGJController::class, 'logout'])->name('gj-logout');
    
    Route::get('/import/{id}', [ImportController::class, 'import'])->name('import');
    
    Route::get('/player/skins', [PlayerSkinController::class, 'index'])->name('player-skins');
    Route::post('/player/skin/create', [PlayerSkinController::class, 'store'])->name('player-skin-store');
    Route::get('/player/skin/duplicate', [PlayerSkinController::class, 'duplicate'])->name('player-skin-duplicate');
    Route::post('/player/skin/delete/{id}', [PlayerSkinController::class, 'destroyAsAdmin'])->name('player-skin-destroy-admin');
    Route::get('/player/skin/delete', [PlayerSkinController::class, 'destroy'])->name('player-skin-destroy');
    
    Route::get('/skins/public', function(){ return redirect()->route('skins-newest');})->name('skins');
    Route::get('/skins/public/new', [SkinController::class, 'newestpublicskins'])->name('skins-newest');
    Route::get('/skins/public/popular', [SkinController::class, 'popularpublicskins'])->name('skins-popular');
    Route::get('/skins/public/{uuid}', [SkinController::class, 'show'])->name('skin-show');
    Route::get('/skins/my', [SkinController::class, 'myskins'])->name('skins-my');
    Route::get('/skin/create', [SkinController::class, 'create'])->name('skin-create');
    Route::post('/skin/create', [SkinController::class, 'store'])->name('skin-store');
    Route::get('/skin/{uuid}/edit', [SkinController::class, 'edit'])->name('skin-edit');
    Route::post('/skin/{uuid}/edit', [SkinController::class, 'update'])->name('skin-update');
    Route::get('/skin/{uuid}/delete', [SkinController::class, 'destroy'])->name('skin-destroy');
    Route::get('/skin/{uuid}/apply', [SkinController::class, 'apply'])->name('skin-apply');
    Route::get('/skin/{uuid}/like', [SkinController::class, 'like'])->name('skin-like');
    
    /*
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/user/{gjid}', [UserController::class, 'show'])->name('user-show');
        Route::get('/user/edit/{gjid}', [UserController::class, 'edit'])->name('user-edit');
        Route::post('/user/edit/{gjid}', [UserController::class, 'update'])->name('user-update');
    */
    
    Route::get('/uploaded/skins', [UploadedSkinController::class, 'index'])->name('uploaded-skins');
    Route::post('/uploaded/skin/delete/{id}', [UploadedSkinController::class, 'destroy'])->name('uploaded-skin-destroy');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['middleware' => ['role:super-admin|admin']], function () {
        // USERS
        Route::resource('users', UserController::class);
        // ROLES
        Route::resource('roles', RoleController::class);
        // PERMISSIONS
        Route::resource('permissions', PermissionController::class);
    });

});


