<?php
    namespace Client\pages\DetailAlbumAdmin;
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
    <link rel="stylesheet" href="/../..//Client/pages/DetailAlbumAdmin/DeleteSong.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <title>Binotify - Album Detail - Admin</title>
</head>
<body>
    <div id="split_page">
        <?php include "Client/components/NavbarAdmin/sidebar_admin.php"; ?>
        <main id="main_body">
            <?php include "Client/components/NavbarAdmin/topbar_admin.php"; ?>
            <section id="delete_song_section">
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
                        <div class="item-4">
                            <h3>Act</h3>
                        </div>
                    </div>
                    <hr/>

                    <?php if (empty($songs)): ?>
                        <h2>- There is no song to delete -</h2>
                        <hr/>
                    <?php endif; ?>
                    
                    <?php foreach ($songs as $key=>$song): ?>
                    <div class="song-item">
                        <div class="item-1">
                            <h3> <?= $key + 1; ?> </h3>
                        </div>
                        <div class="item-2">
                            <h3><?= $song['judul']; ?></h3>
                            <h4><?= $song['penyanyi']; ?></h4>
                        </div>
                        <div class="item-3">
                            <h3><?= $song['genre']; ?></h3>
                        </div>
                        <div class="item-3" >
                            <h3><?= date('Y', strtotime($song['tanggal_terbit'])); ?></h3>
                        </div>
                        <div class="item-4" id="<?= 'child5_'.$song['song_id']; ?>" onclick={deleteSongHandler(event)}>
                            <svg id="<?= 'child51_'.$song['song_id']; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                <path id="<?= 'child511_'.$song['song_id']; ?>" strokeLinecap="round" strokeLinejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <hr/>
                    <?php endforeach; ?>
                    </div>
                </div>
                <!-- page here  -->
            </section>
        </main>
    </div>
</body>
<script>
    const deleteSongHandler = (e) => {
        e.preventDefault();
        const album_id = <?= $album['album_id'] ?>;
        const songId = (e.target.id).split('_')[1];
        const params = "id=" + album_id;
        console.log('Song ID: ' + songId);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/detail-album/delete-song");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/detail-album?" + params;
            };
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`song_id=${songId}&album_id=${album_id}`);
    };
</script>
</html>
