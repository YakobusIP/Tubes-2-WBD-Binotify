<?php
declare(strict_types=1);

namespace Server;

include "Server/Router/Router.php";
include "Server/Controllers/HomeController.php";
include "Server/Controllers/UserController.php";
include "Server/Controllers/AlbumController.php";
include "Server/Controllers/SongsController.php";
include "Server/Controllers/PremiumController.php";

$router = new \Server\Router\Router();

$router->get('/', function() {
    header('Location: /home');
    
});
$router->get('/home', \Server\Controllers\HomeController::class.'@view');
$router->post('/home', \Server\Controllers\HomeController::class.'@insert');
$router->get('/home/logout', \Server\Controllers\HomeController::class.'@logout');
$router->get('/home', \Server\Controllers\SongsController::class.'@songList10View');

$router->get('/register', \Server\Controllers\UserController::class.'@registerview');
$router->post('/register', \Server\Controllers\UserController::class.'@register');
$router->post('/register/search-email', \Server\Controllers\UserController::class.'@findEmail');
$router->post('/register/search-username', \Server\Controllers\UserController::class.'@findByUsername');

$router->get('/login', \Server\Controllers\UserController::class.'@loginview');
$router->post('/login', \Server\Controllers\UserController::class.'@login');

$router->get('/album-list', \Server\Controllers\AlbumController::class.'@albumListView');
$router->get('/add-album', \Server\Controllers\AlbumController::class.'@addAlbumView');
$router->post('/add-album', \Server\Controllers\AlbumController::class.'@addAlbum');
$router->get('/detail-album', \Server\Controllers\AlbumController::class.'@detailAlbumView');
$router->post('/detail-album/edit-album', \Server\Controllers\AlbumController::class.'@editAlbum');
$router->post('/detail-album/delete-album', \Server\Controllers\AlbumController::class.'@deleteAlbum');
$router->get('/detail-album/add-song', \Server\Controllers\AlbumController::class.'@addSongView');
$router->post('/detail-album/add-song', \Server\Controllers\AlbumController::class.'@addSong');
$router->get('/detail-album/delete-song', \Server\Controllers\AlbumController::class.'@deleteSongView');
$router->post('/detail-album/delete-song', \Server\Controllers\AlbumController::class.'@deleteSong');

$router->get('/song-detail', \Server\Controllers\SongsController::class.'@songdetailview');
$router->get('/song-detail/get-song-data', \Server\Controllers\SongsController::class.'@getSong');

$router->get('/add-song', \Server\Controllers\SongsController::class.'@addsongview');
$router->post('/add-song/create-song', \Server\Controllers\SongsController::class.'@createSong');
$router->post('/song-detail/update-song', \Server\Controllers\SongsController::class.'@updateSong');
$router->post('/song-detail/delete-song', \Server\Controllers\SongsController::class.'@deleteSong');

$router->get('/user-list', \Server\Controllers\UserController::class.'@userlistview');
$router->get('/user-list/get-user-data', \Server\Controllers\UserController::class.'@getAllUsers');

$router->get('/search', \Server\Controllers\SongsController::class.'@searchview');
$router->get('/search/get-genre', \Server\Controllers\SongsController::class.'@getAllGenre');
$router->post('/search/search-result', \Server\Controllers\SongsController::class.'@getSearchResult');

$router->get('/premium-singer-list', \Server\Controllers\PremiumController::class.'@premiumsingerview');
// $router->get('/user-list', \Server\Controllers\PremiumController::class.'@getPremiumSongs');
$router->get('/premium-song-list', \Server\Controllers\PremiumController::class.'@premiumSongListView');
$router->post('/update-subs', \Server\Controllers\PremiumController::class.'@updateSubscription');

$router->post('/subscribe', \Server\Controllers\PremiumController::class.'@subscribe');
$router->get('/poll-data', \Server\Controllers\PremiumController::class.'@poll_data');

$router->run();
