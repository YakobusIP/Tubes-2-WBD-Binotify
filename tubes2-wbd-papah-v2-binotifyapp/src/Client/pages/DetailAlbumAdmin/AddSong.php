<?php
    namespace Client\pages\DetailAlbumAdmin;
    /* @var $album */
    /* @var $songs_avail */
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
    <link rel="stylesheet" href="/../..//Client/pages/DetailAlbumAdmin/AddSong.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <title>Binotify - Album Detail - Admin</title>
</head>
<body>
    <div id="split_page">
        <?php include "Client/components/NavbarAdmin/sidebar_admin.php"; ?>
        <main id="main_body">
            <?php include "Client/components/NavbarAdmin/topbar_admin.php"; ?>
            <section id="add_song_section">
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

                    <?php if (empty($songs_avail)): ?>
                        <h2>- There is no song to add -</h2>
                        <hr/>
                    <?php endif; ?>

                    <?php foreach ($songs_avail as $key=>$song): ?>
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
                        <div class="item-4" id="<?= 'child5_'.$song['song_id']; ?>" onclick={addSongHandler(event)}>
                            <svg id="<?= 'child51_'.$song['song_id']; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                <path id="<?= 'child511_'.$song['song_id']; ?>" strokeLinecap="round" strokeLinejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
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
    const addSongHandler = (e) => {
        e.preventDefault();
        const songId = (e.target.id).split('_')[1];
        const albumId = <?= $album['album_id'] ?>;
        const params = "id="+albumId;
        console.log('Song ID: ' + songId);
        console.log('Album ID: ' + albumId);
        console.log('Params: ' + params);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/detail-album/add-song");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/detail-album?"+params;
            };
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`album_id=${albumId}&song_id=${songId}`);
    };
</script>
</html>
