<?php
namespace Client\pages\Home;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/../../Client/App.css">
        <link rel="stylesheet" href="/../..//Client/pages/Home/Home.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotify - Web Player</title>
    </head>
    <body>
        <div id="split_page">
            <?php if (!isset($_SESSION["isadmin"])){
                    include "Client/components/NavbarNonUser/sidebar_nonuser.php";
                }
                elseif($_SESSION["isadmin"]){
                    include "Client/components/NavbarAdmin/sidebar_admin.php";
                }
                else{
                    include "Client/components/NavbarUser/sidebar_user.php";
                }
            ?> 
            <main id="main_body">
                <?php if (!isset($_SESSION["isadmin"])){
                        include "Client/components/NavbarNonUser/topbar_nonuser.php";
                    }
                    elseif($_SESSION["isadmin"]){
                        include "Client/components/NavbarAdmin/topbar_admin.php";
                    }
                    else{
                        include "Client/components/NavbarUser/topbar_user.php";
                    }
                ?> 
                <section id="songs">
                    <?php
                        if(isset($_SESSION["full_name"])){
                            echo "<h1>Welcome, ". $_SESSION["full_name"] ."</h1>";
                        } 
                    ?>
                    
                    <h2>TOP 10 SONGS</h2>
                    <div class="songs-list">
                        <div class="songs-header">
                            <div class="song-image">
                                <h3>  </h3>
                            </div>
                            <div class="song-title-singer">
                                <h3>Title</h3>
                            </div>
                            <div class="song-genre-year">
                                <h3>Genre</h3>
                            </div>
                            <div class="song-genre-year">
                                <h3>Year</h3>
                            </div>
                        </div>
                        <hr/>

                        <?php foreach ($song_list as $key=>$song): ?>
                        <div class="song-card" id="<?= 'song_'.$song['song_id'];?>" onclick={detailSong(event)}>
                            <div class="song-image" id="<?= 'detail1_'.$song['song_id']; ?>">
                                <img id="<?= 'detail11_'.$song['song_id']; ?>" src="../../<?= $song['image_path'];?>" class="cover-song">
                            </div>
                            <div class="song-title-singer" id="<?= 'detail2_'.$song['song_id']; ?>">
                                <h3 id="<?= 'detail21_'.$song['song_id']; ?>"><?= $song['judul']; ?></h3>
                                <h4 id="<?= 'detail22_'.$song['song_id']; ?>"><?= $song['penyanyi']; ?></h4>
                            </div>
                            <div class="song-genre-year" id="<?= 'detail3_'.$song['song_id']; ?>">
                                <h3 id="<?= 'detail31_'.$song['song_id']; ?>"><?= $song['genre']; ?></h3>
                            </div>
                            <div class="song-genre-year" id="<?= 'detail4_'.$song['song_id']; ?>">
                                <h3 id="<?= 'detail41_'.$song['song_id']; ?>"><?= date('Y', strtotime($song['tanggal_terbit'])); ?></h3>
                            </div>
                        </div>
                        <hr/>
                        <?php endforeach;?>
                    </div>
                </section>
            </main>
        </div>
    </body>
    <script>
        const detailSong = (e) =>{
            e.preventDefault();
            const songId = (e.target.id).split('_')[1];

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/song-detail");
            xhr.onload = () =>{
                console.log('DONE: ', xhr.status);
                if(xhr.status===200){
                    window.location.href = "/song-detail?id="+songId;
                }
            }
            xhr.send(null);
        };
    </script>
</html>
