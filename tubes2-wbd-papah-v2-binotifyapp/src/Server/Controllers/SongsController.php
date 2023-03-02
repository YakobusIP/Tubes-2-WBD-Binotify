<?php 

    namespace Server\Controllers;
    use PDOException;
    use PDO;

    class SongsController extends Controller {
        public function __construct()
        {
            parent::__construct();
        }

        public function songdetailview(array $params = []) {
            $song_id = $_GET['id'];
            $song = $this->getSongfromID($song_id);
            if ($song["album_id"] != null) {
                $album_id = (int)$song["album_id"];
                $song_album = $this->getAlbumfromID($album_id);
            } else {
                $album_id = -1;
            }
            $duration = $this->formatDuration((int)$song["duration"]);
            $album_list = $this->getMatchingAlbum($song["penyanyi"]);
            include 'Client/pages/SongDetails/SongDetails.php';
        }

        public function addsongview(array $params = []) {
            if (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
                $album_list = $this->getAllAlbums();
                include 'Client/pages/AddSongs/AddSongs.php';
            } else {
                include 'Client/pages/Errors/Unauthorized.php';
            }
        }

        public function songList10view(array $params = []){
            $song_list = $this->get10Newest();
            include 'Client/pages/Home/Home.php';
        }

        public function searchview(array $params = []) {
            $genre_list = $this->getAllGenre();
            $search_query = $_GET['search_query'];
            $genre = $_GET['genre'];
            $sort_by = $_GET['sort_by'];
            $asc = $_GET['asc'];
            $page = $_GET['page'];
            $search_result = $this->getSearchResult($search_query, $genre, $sort_by, $asc, $page);
            //var_dump($search_result["data"]);
            include 'Client/pages/Search/Search.php';
        }

        // Function to create a song
        public function createSong() {
            try {
                $album = $_POST['album'];
                $int_album = (int)$album;
                $genre = $_POST['genre'];
                $title = $_POST['title'];
                $singer = $_POST['singer'];
                $date = $_POST['date'];
                $duration = $_POST['duration'];
                $int_duration = (int)$duration;
                $image_name = $_FILES['image']['name'];
                $audio_name = $_FILES['audio']['name'];
                
                // Randomize filename
                $temp_image = explode(".", $image_name);
                $new_imagename = round(microtime(true)) . "." . end($temp_image);
                $image_path = 'uploads/img/' . $new_imagename;
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
                $temp_audio = explode(".", $audio_name);
                $new_audioname = round(microtime(true)) . "." . end($temp_audio);
                $audio_path = "uploads/audio/" . $new_audioname;
                move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path);

                if ($int_album == 0) {
                    $query = $this->database->prepare("INSERT INTO binotify_song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $query->execute(array($title, $singer, $date, $genre, $int_duration, $audio_path, $image_path));
                } else {
                    $query = $this->database->prepare("INSERT INTO binotify_song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $query->execute(array($title, $singer, $date, $genre, $int_duration, $audio_path, $image_path, $int_album));
                }                

                $song_id = $this->database->lastInsertId();
                http_response_code(200);
                print_r(json_encode(array(
                    "status" => 200,
                    "song_id" => $song_id,
                    "message" => "Song Successfully Added"
                )));
            } catch (PDOException $e) {
                http_response_code(500);
                print_r(json_encode((array(
                    "status" => 500,
                    "message" => "Failed to add song to database"
                ))));
            }
            
        }
        
        // Function to get song data based on the song id
        public function getSongfromID($song_id) {
            // Fetch song data
            $query = $this->database->prepare("SELECT * FROM binotify_song WHERE song_id = ?");
            $query->execute(array((int)$song_id));
            $result = $query->fetchAll();
            return $result[0];

            // Fetch album data
            // $query2 = $this->database->prepare("SELECT * FROM binotify_album WHERE album_id = ?");
            // $query2->execute(array((int)$result1[0]["album_id"]));
            // $result2 = $query2->fetchAll();
            // print_r(json_encode(array($result1, $result2)));
        }

        public function getAlbumfromID($album_id) {
            // Fetch album data
            $query = $this->database->prepare("SELECT * FROM binotify_album WHERE album_id = ?");
            $query->execute(array((int)$album_id));
            $result = $query->fetchAll();
            return $result[0];
        }

        public function formatDuration($seconds) {
            $minutes = floor(floor($seconds / 60) % 60);
            $seconds = $seconds % 60;
            $duration = array(
                'minutes' => $minutes,
                'seconds' => $seconds
            );
            return $duration;
        }

        // Function to update song data based on the song id
        public function updateSong() {
            try {
                $album = $_POST['album'];
                if ($album == "null") {
                    $int_album = null;
                } else {
                    $int_album = (int)$album;
                }
                
                $genre = $_POST['genre'];
                $title = $_POST['title'];
                $date = $_POST['date'];
                $duration = $_POST['duration'];
                $int_duration = (int)$duration;
                $song_id = $_POST['song_id'];
                $audio_path = "";
                $image_path = "";
                
                if ($_POST["image"] != "null") {
                    $image_name = $_FILES['image']['name'];
                    $temp_image = explode(".", $image_name);
                    $new_imagename = round(microtime(true)) . "." . end($temp_image);
                    $image_path = 'uploads/img/' . $new_imagename;
                    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

                    $stmt = "SELECT image_path FROM binotify_song WHERE song_id = ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($song_id));
                    $result = $query->fetchAll();
                    unlink($result[0]["image_path"]);
                } 
                
                if ($_POST["audio"] != "null") {
                    $audio_name = $_FILES['audio']['name'];
                    $temp_audio = explode(".", $audio_name);
                    $new_audioname = round(microtime(true)) . "." . end($temp_audio);
                    $audio_path = "uploads/audio/" . $new_audioname;
                    move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path);

                    $stmt = "SELECT audio_path FROM binotify_song WHERE song_id = ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($song_id));
                    $result = $query->fetchAll();
                    unlink($result[0]["audio_path"]);
                }
                
                if ($_POST["image"] == "null" && $_POST["audio"] != "null") {
                    $stmt = "UPDATE binotify_song SET judul = ?, tanggal_terbit = ?, genre = ?, duration = ?, audio_path = ?, album_id = ? WHERE song_id = ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($title, $date, $genre, $int_duration, $audio_path, $int_album, $song_id));
                } elseif ($_POST["image"] != "null" && $_POST["audio"] == "null") {
                    $stmt = "UPDATE binotify_song SET judul = ?, tanggal_terbit = ?, genre = ?, image_path = ?, album_id = ? WHERE song_id = ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($title, $date, $genre, $image_path, $int_album, $song_id));
                } elseif ($_POST["image"] != "null" && $_POST["audio"] != "null") {
                    $stmt = "UPDATE binotify_song SET judul = ?, tanggal_terbit = ?, genre = ?, duration = ?, audio_path = ?, image_path = ?, album_id = ? WHERE song_id = ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($title, $date, $genre, $int_duration, $audio_path, $image_path, $int_album, $song_id));
                } else {
                    $stmt = "UPDATE binotify_song SET judul = ?, tanggal_terbit = ?, genre = ?, album_id = ? WHERE song_id = ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($title, $date, $genre, $int_album, $song_id));
                }

                http_response_code(200);
                print_r(json_encode(array(
                    "status" => 200,
                    "message" => "Song Successfully Updated"
                )));
            } catch (PDOException $e) {
                http_response_code(500);
                print_r(json_encode((array(
                    "status" => 500,
                    "message" => "Failed to update song to database"
                ))));
            }
        }

        public function deleteSong() {
            try {
                $song_id = $_POST['song_id'];
                $query1 = $this->database->prepare("SELECT image_path, audio_path FROM binotify_song WHERE song_id = ?");
                $query1->execute(array($song_id));
                $result = $query1->fetchAll();
    
                // Remove old files
                unlink($result[0]["image_path"]);
                unlink($result[0]["audio_path"]);
    
                $query2 = $this->database->prepare("DELETE FROM binotify_song WHERE song_id = ?");
                $query2->execute(array($song_id));

                http_response_code(200);
                print_r(json_encode(array(
                    "status" => 200,
                    "message" => "Song Successfully Deleted"
                )));
            } catch (PDOException $e) {
                http_response_code(500);
                print_r(json_encode((array(
                    "status" => 500,
                    "message" => "Failed to delete song from database"
                ))));
            }
            
        }
        
        // Function to get x newest and sorted by title song
        public function get10Newest(){
            $query = $this->database->prepare("SELECT * FROM (SELECT song_id, judul, penyanyi, tanggal_terbit, genre, image_path FROM binotify_song ORDER BY song_id DESC LIMIT 10) AS terbaru ORDER BY judul ASC");
            $query->execute();
            $listOfSongs = $query->fetchAll();
            return $listOfSongs;
        }

        public function getSearchResult($search_query, $genre, $sort_by, $asc, $page) {
            $limit = 5;
            $offset = ((int)$page - 1) * $limit;

            if ($asc == "true") {
                if ($genre == "All") {
                    $stmt = "SELECT * FROM binotify_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by ASC LIMIT ? OFFSET ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array('%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%', $limit, $offset));

                    $stmt = "SELECT * FROM binotify_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by ASC";
                    $all_query = $this->database->prepare($stmt);
                    $all_query->execute(array('%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%'));
                } else {
                    $stmt = "SELECT * FROM (SELECT * FROM binotify_song WHERE genre = ?) AS filter_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by ASC LIMIT ? OFFSET ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($genre, '%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%', $limit, $offset));

                    $stmt = "SELECT * FROM (SELECT * FROM binotify_song WHERE genre = ?) AS filter_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by ASC";
                    $all_query = $this->database->prepare($stmt);
                    $all_query->execute(array($genre, '%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%'));
                }
            } else {
                if ($genre == "All") {
                    $stmt = "SELECT * FROM binotify_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by DESC LIMIT ? OFFSET ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array('%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%', $limit, $offset));

                    $stmt = "SELECT * FROM binotify_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by DESC";
                    $all_query = $this->database->prepare($stmt);
                    $all_query->execute(array('%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%'));
                } else {
                    $stmt = "SELECT * FROM (SELECT * FROM binotify_song WHERE genre = ?) AS filter_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by DESC LIMIT ? OFFSET ?";
                    $query = $this->database->prepare($stmt);
                    $query->execute(array($genre, '%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%', $limit, $offset));

                    $stmt = "SELECT * FROM (SELECT * FROM binotify_song WHERE genre = ?) AS filter_song WHERE judul LIKE ? OR penyanyi LIKE ? OR to_char(tanggal_terbit, 'YYYY-MM-DD') LIKE ? ORDER BY $sort_by DESC";
                    $all_query = $this->database->prepare($stmt);
                    $all_query->execute(array($genre, '%'.$search_query.'%', '%'.$search_query.'%', '%'.$search_query.'%'));
                }
            }
            
            $result = $query->fetchAll();
            $total = $all_query->rowCount();
            return(array(
                "data" => $result,
                "last_page" => ceil($total/$limit)
            ));
            
        }

        public function getAllGenre() {
            $query = $this->database->prepare("SELECT DISTINCT genre FROM binotify_song");
            $query->execute();
            $result = $query->fetchAll();
            return $result;
        }

        public function getAllAlbums() {
            $query = $this->database->prepare("SELECT * FROM binotify_album");
            $query->execute();
            $result = $query->fetchAll();
            return $result;
        }

        public function getMatchingAlbum($singer) {
            $query = $this->database->prepare("SELECT * FROM binotify_album WHERE penyanyi = ?");
            $query->execute(array($singer));
            $result = $query->fetchAll();
            return $result;
        }

    }
?>