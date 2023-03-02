<?php
    namespace Client\pages\PremiumSongList;
    /* @var $songs */
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
    <link rel="stylesheet" href="/../..//Client/App.css">
    <link rel="stylesheet" href="/../../Client/pages/PremiumSongList/PremiumSongList.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.2.1/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.2.1/css/sharp-solid.css">
    <link rel="stylesheet" href="https://fonticons-free-fonticons.netdna-ssl.com/kits/1ce05b4b/publications/122070/woff2.css">
    <title>Binotify - Premium Song List</title>
</head>
<body>
<div id="split_page">
        <?php 
        if (isset($_SESSION["isadmin"])) {
            $_SESSION["isadmin"] ? include "Client/components/NavbarAdmin/sidebar_admin.php"
            : include "Client/components/NavbarUser/sidebar_user.php";
        } else {
            include "Client/components/NavbarNonUser/sidebar_nonuser.php";
        }
        ?>
        <main id="main_body">
            <?php 
            if (isset($_SESSION["isadmin"])) {
                $_SESSION["isadmin"] ? include "Client/components/NavbarAdmin/topbar_admin.php" 
                : include "Client/components/NavbarUser/topbar_user.php";
            } else {
                include "Client/components/NavbarNonUser/topbar_nonuser.php";
            }
            ?>
            <section id="premium_song_list_section">
                <!-- page here -->
                <div class="header">
                    <h1>PREMIUM SONG LIST | Singer-<?= $singer_id ;?></h1>
                </div>
                <div class="song-list-container">
                    <table id="song_table">
                        <tr class="trH">
                            <th class="th-num">#</th>
                            <th class="th-title">Title</th>
                            <th class="th-play">Play/Pause</th>
                        </tr>
                        <!-- songs here -->
                        <?php if(!empty($songs)): ?>
                            <?php foreach ($songs as $key=>$song): ?>
                                <tr id="<?= 'trD'.$song['song_id']; ?>" class="trD">
                                    <td class="td-num"><?= $key + 1; ?></td>
                                    <td class="td-title"><?= $song['judul']; ?></td>
                                    <td id="<?= 'td_play'.$song['song_id']; ?>" class="td-play" onclick={songEvent(event)}>
                                        <i id="<?= 'play_btn'.$song['song_id']; ?>" class="fa-duotone fa-play"></i>
                                        <i id="<?= 'pause_btn'.$song['song_id']; ?>" class="fa-solid fa-pause"></i>
                                        <audio id="<?= 'audio'.$song['song_id']; ?>" src="<?= "http://localhost:4000/" . $song["audio_path"] ?>"></audio>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="trD">
                                <td></td>
                                <td class="td-empty">THERE IS NO PREMIUM SONG</td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                        <!-- songs here -->
                    </table>
                </div>
                <!-- page here -->
            </section>
        </main>
    </div>
</body>
<script>
    var playing = false;
    var songIdPlaying = null;
    const songEvent = (e) => {
        // get clicked area information
        const songId = (e.target.id)[e.target.id.length - 1];
        const songElmt = document.getElementById("trD" + songId);
        const songAudio = document.getElementById("audio" + songId);
        console.log(songAudio.src);
        if (!playing) {
            // playing a song
            songAudio.play();
            songElmt.classList.add('row-playing');
            playing = true;
            songIdPlaying = songId;
            console.log("PLAY songIdPlaying: " + songIdPlaying)
        } else {
            if(songIdPlaying == songId) {
                // pause the playing song
                songAudio.pause();
                console.log("PAUSE songIdPlaying: " + songIdPlaying)
                playing = false;
                songIdPlaying = null;
                songElmt.classList.remove('row-playing');
            } else {
                // pause the playing song
                const songElmtPlaying = document.getElementById("trD" + songIdPlaying);
                const songAudioPlaying = document.getElementById("audio" + songIdPlaying);
                songAudioPlaying.pause();
                songElmtPlaying.classList.remove('row-playing');
                console.log("PAUSE songIdPlaying: " + songIdPlaying);
                // playing another one
                songAudio.play();
                songElmt.classList.add('row-playing');
                songIdPlaying = songId;
                console.log("PLAY songIdPlaying: " + songIdPlaying);
            }
        }
        // console.log(songId);
        // console.log(songElmt);
    };
</script>
</html>
