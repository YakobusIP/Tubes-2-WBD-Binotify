<?php

namespace Server\Controllers;
use PDO;

class AlbumController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function albumListView(array $params = [])
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $num_start = $page * 5 + 1;
        $max_page = ceil($this->getAlbumsLength()/5);
        $album_list = $this->getAlbumsWithOffset($page);
        include 'Client/pages/AlbumList/AlbumList.php';
    }

    public function addAlbumView(array $params = [])
    {
        if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
            include 'Client/pages/AddAlbum/AddAlbum.php';
        } else {
            include 'Client/pages/Errors/Unauthorized.php';
        }
    }

    public function detailAlbumView(array $params = [])
    {
        $album = $this->getAlbumById($params['id']);
        $duration = $this->secToDetailTime($album['total_duration']);
        $songs = $this->getSongsByAlbumId($params['id']);
        if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
            include 'Client/pages/DetailAlbumAdmin/DetailAlbumAdmin.php';
        } else {
            include 'Client/pages/DetailAlbum/DetailAlbum.php';
        }
    }

    public function addSongView(array $params = [])
    {
        $album = $this->getAlbumById($params['id']);
        $duration = $this->secToDetailTime($album['total_duration']);
        $songs_avail = $this->getSongsAvail($params['penyanyi']);
        if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
            include 'Client/pages/DetailAlbumAdmin/AddSong.php';
        }
    }

    public function deleteSongView(array $params = [])
    {
        $album = $this->getAlbumById($params['id']);
        $duration = $this->secToDetailTime($album['total_duration']);
        $songs = $this->getSongsByAlbumId($params['id']);
        if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
            include 'Client/pages/DetailAlbumAdmin/DeleteSong.php';
        }
    }

    public function addAlbum(array $params = array())
    {
        // print_r(json_encode($_POST));
        $judul = $_POST['judul'];
        $penyanyi = $_POST['penyanyi'];
        $genre = $_POST['genre'];
        $tanggal_terbit = $_POST['tanggal_terbit'];
        $image_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
        $image_path = 'uploads/img/'.$image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        try {
            $sql = "INSERT INTO binotify_album (judul, penyanyi, total_duration, image_path, tanggal_terbit, genre) VALUES (?, ?, 0, ?, ?, ?)";
            $query = $this->database->prepare($sql);
            $query->execute(array($judul, $penyanyi, $image_path, $tanggal_terbit, $genre));
            
            $sql = "SELECT album_id FROM binotify_album ORDER BY album_id DESC LIMIT 1";
            $query = $this->database->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            $album_id = $result[0]['album_id'];
            
            http_response_code(201);
            print_r(json_encode(array(
                'status' => 201,
                'album_id' => $album_id,
                'message' => "Album has been created",
            )));
        } catch (PDOException $e) {
            if($e.getCode()==23000){
                http_response_code(400);
                print_r(json_encode(array(
                    'status' => 400,
                    'message' => "Code: 400",
                )));
            } else {
                http_response_code(500);
                print_r(json_encode(array(
                    'status' => 500,
                    'message' => "Internal server error",
                )));
            }
        }
    }

    public function editAlbum(array $params = [])
    {
        print_r(json_encode($_POST));
        $album_id = (int)$_POST['album_id'];
        $album =  $this->getAlbumById($album_id);
        $judul = $_POST['judul'];
        $genre = $_POST['genre'];
        $tanggal_terbit = $_POST['tanggal_terbit'];
        $image_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
        $image_path = 'uploads/img/'.$image_name;

        // var_dump($_SERVER['DOCUMENT_ROOT']);
        
        if(!trim($_POST['judul'] ?? '')) {
            $judul = $album['judul'];
        } 
        if(!trim($_POST['genre'] ?? '')) {
            $genre = $album['genre'];
        } 
        if(!trim($_POST['tanggal_terbit'] ?? '')) {
            $tanggal_terbit = $album['tanggal_terbit'];
        } 
        if($image_name == ''){
            $image_path = $album['image_path'];
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }

        try {
            $sql = "UPDATE binotify_album SET judul=?, image_path=?, tanggal_terbit=?, genre=? WHERE album_id=?";
            $query = $this->database->prepare($sql);
            $query->execute(array($judul, $image_path, $tanggal_terbit, $genre, $album_id));

            http_response_code(201);
            print_r(json_encode(array(
                'status' => 201,
                'message' => "Album has been edited",
            )));
        } catch (PDOException $e) {
            if($e.getCode()==23000){
                http_response_code(400);
                print_r(json_encode(array(
                    'status' => 400,
                    'message' => "Code: 400",
                )));
            } else {
                http_response_code(500);
                print_r(json_encode(array(
                    'status' => 500,
                    'message' => "Internal server error",
                )));
            }
        }
    }

    public function deleteAlbum(array $params = [])
    {
        print_r(json_encode($_POST));
        try {
            $sql1 = "UPDATE binotify_song SET album_id = NULL WHERE album_id = ?";
            $query1 = $this->database->prepare($sql1);
            $query1->execute(array($_POST['album_id']));

            $sql2 = "DELETE FROM binotify_album WHERE album_id = ?";
            $query2 = $this->database->prepare($sql2);
            $query2->execute(array($_POST['album_id']));

            http_response_code(201);
            print_r(json_encode(array(
                'status' => 201,
                'message' => "Album has been deleted",
            )));
        } catch (PDOException $e) {
            if($e.getCode()==23000){
                http_response_code(400);
                print_r(json_encode(array(
                    'status' => 400,
                    'message' => "Code: 400",
                )));
            } else {
                http_response_code(500);
                print_r(json_encode(array(
                    'status' => 500,
                    'message' => "Internal server error",
                )));
            }
        }
    }

    public function addSong(array $params = [])
    {
        print_r(json_encode($_POST));
        try {
            $sql = "UPDATE binotify_song SET album_id = ? WHERE song_id = ?";
            $query = $this->database->prepare($sql);
            $query->execute(array($_POST['album_id'], $_POST['song_id']));

            $sql1 = "SELECT duration FROM binotify_song WHERE song_id = ?";
            $query1 = $this->database->prepare($sql1);
            $query1->execute(array($_POST['song_id']));
            $song_duration = $query1->fetchAll()[0]['duration'];
            
            $sql2 = "UPDATE binotify_album SET total_duration = total_duration + ? WHERE album_id = ?";
            $query2 = $this->database->prepare($sql2);
            $query2->execute(array($song_duration, $_POST['album_id']));

            http_response_code(201);
            print_r(json_encode(array(
                'status' => 201,
                'message' => "Song has been added",
            )));
        } catch (PDOException $e) {
            if($e.getCode()==23000){
                http_response_code(400);
                print_r(json_encode(array(
                    'status' => 400,
                    'message' => "Code: 400",
                )));
            } else {
                http_response_code(500);
                print_r(json_encode(array(
                    'status' => 500,
                    'message' => "Internal server error",
                )));
            }
        }
    }

    public function deleteSong(array $params = [])
    {
        print_r(json_encode($_POST));
        try {
            $sql = "UPDATE binotify_song SET album_id = NULL WHERE song_id = ?";
            $query = $this->database->prepare($sql);
            $query->execute(array($_POST['song_id']));
            
            $sql1 = "SELECT duration FROM binotify_song WHERE song_id = ?";
            $query1 = $this->database->prepare($sql1);
            $query1->execute(array($_POST['song_id']));
            $song_duration = $query1->fetchAll()[0]['duration'];

            $sql2 = "UPDATE binotify_album SET total_duration = total_duration - ? WHERE album_id = ?";
            $query2 = $this->database->prepare($sql2);
            $query2->execute(array($song_duration, $_POST['album_id']));

            http_response_code(201);
            print_r(json_encode(array(
                'status' => 201,
                'message' => "Song has been deleted",
            )));
        } catch (PDOException $e) {
            if($e.getCode()==23000){
                http_response_code(400);
                print_r(json_encode(array(
                    'status' => 400,
                    'message' => "Code: 400",
                )));
            } else {
                http_response_code(500);
                print_r(json_encode(array(
                    'status' => 500,
                    'message' => "Internal server error",
                )));
            }
        }
    }

    public function getAlbums()
    {
        $query = $this->database->prepare("SELECT * FROM binotify_album ORDER BY judul ASC");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function getAlbumsWithOffset($page)
    {
        $query = $this->database->prepare("SELECT * FROM binotify_album ORDER BY judul ASC LIMIT 5 OFFSET ?");
        $query->execute(array($page * 5));
        $result = $query->fetchAll();
        return $result;
    }

    public function getAlbumsLength() {
        $query = $this->database->prepare("SELECT COUNT(album_id) FROM binotify_album");
        $query->execute();
        $result = $query->fetchAll();
        return $result[0]['count'];
    }

    public function getSongsByAlbumId($albumId)
    {
        $query = $this->database->prepare("SELECT * FROM binotify_song WHERE album_id = ? ORDER BY judul ASC");
        $query->execute(array($albumId));
        $result = $query->fetchAll();
        return $result;
    }

    public function getAlbumById($albumId)
    {
        $query = $this->database->prepare("SELECT * FROM binotify_album WHERE album_id = ?");
        $query->execute(array($albumId));
        $result = $query->fetchAll();
        return $result[0];
    }

    public function getSongsAvail($rawPenyanyi)
    {
        $penyanyi = explode("%20", $rawPenyanyi)[0];
        $sql = "SELECT * FROM binotify_song WHERE penyanyi=? AND album_id IS NULL";
        $query = $this->database->prepare($sql);
        $query->execute(array($penyanyi));
        $result = $query->fetchAll();
        return $result;
    }

    public function secToDetailTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(floor($seconds / 60) % 60);
        $seconds = $seconds % 60;
        $duration = array(
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        );
        return $duration;
    }
}
