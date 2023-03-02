<?php
    namespace Client\pages\DetailAlbum;
    /* @var $album */
    /* @var $songs */
    /* @var $duration */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../Client/App.css">
    <link rel="stylesheet" href="/../..//Client/pages/DetailAlbum/DetailAlbum.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <title>Binotify - Album Details</title>
</head>
<body>
    <div id="split_page">
        <?php 
            isset($_SESSION["isadmin"]) ? include "Client/components/NavbarUser/sidebar_user.php"
            : include "Client/components/NavbarNonUser/sidebar_nonuser.php";
        ?>
        <main id="main_body">
            <?php 
                isset($_SESSION["isadmin"]) ? include "Client/components/NavbarUser/topbar_user.php"
                : include "Client/components/NavbarNonUser/topbar_nonuser.php";
            ?>
            <section id="detail_album_section">
                <!-- page here  -->
                <div class="album-field">
                    <div class="album-cover">
                        <!-- cover here -->
                        <img src='/../../<?= $album['image_path'] ?>'/>
                    </div>
                    <div class="album-info">
                        <h1><?= $album['judul']; ?></h1>
                        <div class="info-item">
                            <h4><?= $album['penyanyi']; ?></h4>
                            <h4 class="dot"> ● </h4>
                            <h4><?= $album['tanggal_terbit']; ?></h4>
                            <h4 class="dot"> ● </h4>
                            <h4>
                                <?= $duration['hours'] != 0 ? $duration['hours'].' hr ' : ''; ?>
                                <?= $duration['minutes'] != 0 ? $duration['minutes'].' min ' : ''; ?>
                                <?= $duration['seconds'].' sec '; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            
                <div class="song-list">
                    <div class="song-header">
                        <div class="item-1">
                            <h3> No </h3>
                        </div>
                        <div class="item-2">
                            <h3>Title</h3>
                        </div>
                        <div class="item-3">
                            <h3>Genre</h3>
                        </div>
                        <div class="item-3">
                            <h3>Year</h3>
                        </div>
                    </div>
                    <hr/>

                    <?php if (empty($songs)): ?>
                        <h2>- There is no song on this album -</h2>
                        <hr/>
                    <?php endif; ?>

                    <?php foreach ($songs as $key=>$song): ?>
                    <div class="song-item" id="<?= 'song_'.$song['song_id']; ?>" onclick={detailSong(event)}>
                        <div class="item-1" id="<?= 'child1_'.$song['song_id']; ?>">
                            <h3 id="<?= 'child11_'.$song['song_id']; ?>"> <?= $key + 1; ?> </h3>
                        </div>
                        <div class="item-2" id="<?= 'child2_'.$song['song_id']; ?>">
                            <h3 id="<?= 'child21_'.$song['song_id']; ?>"><?= $song['judul']; ?></h3>
                            <h4 id="<?= 'child22_'.$song['song_id']; ?>"><?= $song['penyanyi']; ?></h4>
                        </div>
                        <div class="item-3" id="<?= 'child3_'.$song['song_id']; ?>">
                            <h3 id="<?= 'child31_'.$song['song_id']; ?>"><?= $song['genre']; ?></h3>
                        </div>
                        <div class="item-3" id="<?= 'child4_'.$song['song_id']; ?>">
                            <h3 id="<?= 'child41_'.$song['song_id']; ?>"><?= date('Y', strtotime($song['tanggal_terbit'])); ?></h3>
                        </div>
                    </div>
                    <hr/>
                    <?php endforeach; ?>
                </div>
                <!-- page here -->
            </section>
        </main>
    </div>
</body>
<script>
    const detailSong = (e) => {
        e.preventDefault();
        const songId = (e.target.id).split('_')[1];
        console.log("Song ID: " + songId);

        const xhr = new XMLHttpRequest();
        const params = "id="+songId;
        xhr.open("GET", "/song-detail");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/song-detail?"+params;
            }   
        }
        xhr.send(null);
    };
</script>
</html>
