<?php 
    namespace Client\pages\SongDetails;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../..//Client/pages/SongDetails/SongDetails.css">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotify - Song Detail</title>
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
                <section id="song_details">
                    <div id="song_image_container">
                        <img id="song_image" src="<?= $song["image_path"]; ?>" alt="Song Image" />
                    </div>
                    <article id="song_info">
                        <div id="album_genre">
                            <a id="album" href=<?= $album_id != -1 ? "/detail-album?id=" . $album_id : ""; ?>>
                                <?php if($album_id != -1) {
                                    echo $song_album["judul"];
                                } else {
                                    echo "No Album";
                                }?>
                            </a>
                            <h1>.</h1>
                            <p id="genre"><?= $song["genre"]; ?></p>
                            <?php if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]){
                                echo "<svg onclick={editSongHandler(event)} id=\"edit_button\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"currentColor\" class=\"w-6 h-6\">";
                                    echo "<path d=\"M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z\" />";
                                echo "</svg>";
                                echo "<svg onclick={deleteModalHandler(event)} id=\"delete_button\" xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"w-6 h-6\">";
                                    echo "<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0\" />";
                                echo "</svg>";};
                            ?>
                        </div>
                        <p id="song_title"><?= $song["judul"]; ?></p>
                        <div id="song_metadata">
                            <p id="singer"><?= $song["penyanyi"]; ?></p>
                            <h1>.</h1>
                            <p id="date"><?= $song["tanggal_terbit"]; ?></p>
                            <h1>.</h1>
                            <p id="duration">
                                <?= ($duration["minutes"] < 10 ? 
                                "0" . $duration["minutes"] : $duration["minutes"]) 
                                . ":" . 
                                ($duration["seconds"] < 10 ? 
                                "0" . $duration["seconds"] : $duration["seconds"]); ?>
                            </p>
                        </div>
                        
                    </article>
                </section>
                <div id="playbar">
                    <div id="audio_center">
                        <!-- Audio playbar -->
                        <span id="play_button" onclick={playFunction()}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div id="song_timeline">
                            <span id="cur_minutes">00</span><p id="dots">:</p><span id="cur_seconds">00</span>
                            <input id="audio_progress" type="range" min="0"  max="100" value="0" onchange={changeProgress()} />
                            <p id="end_duration"><?= ($duration["minutes"] < 10 ? 
                                "0" . $duration["minutes"] : $duration["minutes"]) 
                                . ":" . 
                                ($duration["seconds"] < 10 ? 
                                "0" . $duration["seconds"] : $duration["seconds"]); ?></p>
                        </div>
                    </div>

                    <!-- Audio volume control -->
                    <div id="audio_control">
                        <span id="mute_button" onclick={muteVolume()}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                            </svg>  
                        </span>
                        <input id="audio_level" type="range" min="0" max="100" value="100" onchange={changeVolume()} />
                    </div>
                    <audio id="audio" src="<?= $song["audio_path"]; ?>"></audio>
                </div>
            </main>
        </div>

        <!-- Edit song modal -->
        <div class="edit-modal-container" id="editModalContainer">
            <div class="edit-modal">
                <h1>EDIT SONG</h1>
                <div class="modal-content">
                    <div class="form-item">
                        <div class="form-label"><h3>Album</h3></div>
                        <select class="form-select" id="new_album">
                            <option value="No Album">No Album</option>
                            <?php foreach($album_list as $key=>$album): ?>
                                <option value="<?= $album["album_id"]; ?>"><?= $album["judul"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-item">
                        <div class="form-label"><h3>Genre</h3></div>
                        <div class="form-input">
                            <input id="new_genre" type="text" value="<?= $song['genre'] ?>" required>
                        </div>         
                    </div>
                    <div class="form-item">
                        <div class="form-label"><h3>Judul</h3></div>
                        <div class="form-input">
                            <input id="new_title" type="text" value="<?= $song['judul'] ?>" required>
                        </div>         
                    </div>
                    <div class="form-item">
                        <div class="form-label"><h3>Singer</h3></div>
                        <div class="form-input">
                            <input id="new_singer" type="text" value="<?= $song['penyanyi'] ?>" readonly="readonly" disabled="disabled">
                        </div>         
                    </div>
                    <div class="form-item">
                        <div class="form-label"><h3>Release Date</h3></div>
                        <div class="form-input">
                            <input id="new_date" type="date" value="<?= $song['tanggal_terbit'] ?>" required>
                        </div>         
                    </div>
                    <div class="form-item">
                        <div class="form-label"><h3>Song Cover</h3></div>
                        <div class="form-input">
                            <input id="editCover" type="file" accept="image/*">
                        </div>         
                    </div>
                    <div class="form-item">
                        <div class="form-label"><h3>Audio File</h3></div>
                        <div class="form-input">
                            <input id="editAudio" type="file" accept="audio/*">
                        </div>         
                    </div>
                </div>
                <div class="choice">
                    <div id="editSaveBtn" onclick={saveEditHandler(event)}>
                        Save
                    </div>
                    <div id="editCancelBtn" onclick={cancelEditHandler(event)}>
                        Cancel
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete song modal -->
        <div class="delete-modal-container" id="deleteModalContainer">
            <div class="delete-modal">
                <h2>Are you sure you want to delete this song?</h2>
                <div class="choice">
                    <div id="deleteYesBtn" onclick="yesDeleteHandler(event)">
                        Yes
                    </div>
                    <div id="deleteCancelBtn" onclick="cancelDeleteHandler(event)">
                        Cancel
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>
        // Modal Handler
        // Admin edit modal

        let album = 'No Album';
        let album_id = -1;

        const editSongHandler = (e) => {
            e.preventDefault();
            const modal_container = document.getElementById('editModalContainer');
            modal_container.classList.add('show');

            var option = document.getElementsByTagName("option");
            for (i=0; i < option.length; i++) {
                if (option[i].outerHTML.substr(15, 1) === "<?= $album_id ?>") {
                    option[i].setAttribute("selected", "selected");
                }
            }
        };

        const saveEditHandler = async (e) => {
            // Close the modal and send datas to backend
            e.preventDefault();
            const modal_container = document.getElementById('editModalContainer');
            modal_container.classList.remove('show');

            var song_id = <?= $song["song_id"]; ?>

            // Gathering required datas
            var album = document.getElementById("new_album").value;
            if (album === "No Album") {
                album = null;
            }

            const genre = document.getElementById("new_genre").value;
            const title = document.getElementById("new_title").value;
            const date = document.getElementById("new_date").value;

            // If new files is not inserted, use original files
            var image = document.getElementById("editCover").files[0];
            if (document.getElementById("editCover").files.length === 0) {
                image = null;
            }

            var audio = document.getElementById("editAudio").files[0];
            if (document.getElementById("editAudio").files.length === 0) {
                audio = null;
            }
            
            // Get audio file duration if exists
            var duration = 0;
            if (audio != null) {
                duration = await getAudioDuration(audio); 
            }

            const id = new URLSearchParams(window.location.search).get('id');

            const xmlhttp = new XMLHttpRequest();
            const formData = new FormData();
            formData.append("album", album);
            formData.append("genre", genre);
            formData.append("title", title);
            formData.append("date", date);
            formData.append("duration", duration);
            formData.append("image", image);
            formData.append("audio", audio);
            formData.append("song_id", id);

            xmlhttp.open("POST", "/song-detail/update-song");
            xmlhttp.onload = () => {
                if (xmlhttp.status === 200) {
                    window.location.href="/song-detail?id=" + song_id;
                }
            }
            xmlhttp.send(formData);

        }

        const cancelEditHandler = (e) => {
            e.preventDefault();
            const modal_container = document.getElementById('editModalContainer');
            modal_container.classList.remove('show');
        }

        // Delete modal
        const deleteModalHandler = (e) => {
            e.preventDefault();
            const modal_container = document.getElementById('deleteModalContainer');
            modal_container.classList.add('show');
        };

        const yesDeleteHandler = (e) => {
            const modal_container = document.getElementById('deleteModalContainer');
            modal_container.classList.remove('show');

            const song_id = new URLSearchParams(window.location.search).get('id');
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/song-detail/delete-song");
            xhr.onload = () => {
                if (xhr.status === 200) {
                    window.location.href = '/album-list'
                }
            }
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`song_id=${song_id}`);
        }
        const cancelDeleteHandler = (e) => {
            const modal_container = document.getElementById('deleteModalContainer');
            modal_container.classList.remove('show');
        }

        // Audio section
        var audio = document.getElementById("audio");
        var volume_bar = document.getElementById("audio_level");
        var mute_button = document.getElementById("mute_button");
        var play_button = document.getElementById("play_button");
        var slider = document.getElementById("audio_progress");

        let m = s = "0" + 0;
        let timer, duration;
        let m_display, s_display = "";
        let progress_bar_value = 0;
        let total_s = 0;
        let isSongPlaying = false;
        let recent_volume = 0;
        let muted = false;

        function muteVolume() {
            if (muted) {
                muted = false;
                volume_bar.value = recent_volume;
                audio.volume = volume_bar.value / 100;
                mute_button.innerHTML =
                    `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>`
            } else {
                muted = true;
                recent_volume = volume_bar.value;
                audio.volume = 0;
                volume_bar.value = 0;
                mute_button.innerHTML =
                    `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75L19.5 12m0 0l2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6l4.72-4.72a.75.75 0 011.28.531V19.94a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.506-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.395C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>`
            }
        }

        function reset_slider() {
            slider.value = 0;
        }

        function changeVolume() {
            audio.volume = volume_bar.value / 100;
        }

        function changeProgress() {
            document.getElementById("audio").currentTime = audio.duration * (document.getElementById("audio_progress").value / 100);
            total_s = Math.floor(document.getElementById("audio").currentTime);
            updateSlider();
        }

        function playFunction() {
            if (!isSongPlaying) {
                playAudio();
            } else {
                pauseAudio();
            }
        }

        function updateSlider() {
            let position = 0;
            
            if (!isNaN(audio.duration)) {
                position = audio.currentTime * (100 / audio.duration);
                slider.value = position

                if (slider.value == 100) {
                    clearInterval(timer);
                    reset_slider();
                    m = "00";
                    s = "00";
                    putValue();
                    play_button.innerHTML = 
                        `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                        </svg>`
                    isSongPlaying = false;
                }
            }
        }

        function putValue() {
            document.getElementById("cur_minutes").innerHTML = m;
            document.getElementById("cur_seconds").innerHTML = s;
        }

        function playAudio() {
            var playedSong;
            if (getCookie("playedSong") == null) {
                playedSong = [];
            } else {
                var cookie_value = getCookie("playedSong");
                playedSong = JSON.parse(cookie_value);
            }

            isSongPlaying = true;
            var duration = audio.duration;

            var current_pos = window.location.href.toString();
            if ("<?= !isset($_SESSION["isadmin"]) ?>" === "1") {
                if (playedSong.length < 3 || playedSong.indexOf(current_pos) !== -1)  {
                    if (playedSong.indexOf(current_pos) === -1) {
                        playedSong.push(current_pos);
                    }
                    
                    setCookie("playedSong", JSON.stringify(playedSong), 1);
                    audio.play();
                    isSongPlaying = true;
                    play_button.innerHTML = 
                        `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
                        </svg>`

                    timer = setInterval(() => {

                        total_s++;
                        s = total_s % 60;
                        m = Math.floor(total_s / 60);

                        if (m < 10) {
                            m = "0" + m;
                        }

                        if (s < 10) {
                            s = "0" + s;
                        } else if(s >= 10 && s < 60) {
                            s = s;
                        }  
                        
                        if(s == 60) {
                            m++;
                            s = "0" + 0;

                            if (m < 10) {
                                m = "0" + m;
                            } else {
                                m = m;
                            }
                        }

                        if (total_s == Math.floor(duration)) {
                            clearInterval(timer);
                            total_s = 0;
                            m = "0" + 0;
                            s = "0" + 0;

                            play_button.innerHTML = 
                                `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                                </svg>`
                        }

                        progress_bar_value = (total_s / Math.floor(duration)) * 100;
                        document.getElementById("audio_progress").value = progress_bar_value;

                        putValue();
                    }, 1000);  
                } else {
                    console.log("Maximum 3 songs has been played!");
                }
            } else {
                audio.play();
                isSongPlaying = true;
                play_button.innerHTML = 
                    `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
                    </svg>`

                timer = setInterval(() => {

                    total_s++;
                    s = total_s % 60;
                    m = Math.floor(total_s / 60);

                    if (m < 10) {
                        m = "0" + m;
                    }

                    if (s < 10) {
                        s = "0" + s;
                    } else if(s >= 10 && s < 60) {
                        s = s;
                    }  
                    
                    if(s == 60) {
                        m++;
                        s = "0" + 0;

                        if (m < 10) {
                            m = "0" + m;
                        } else {
                            m = m;
                        }
                    }

                    if (total_s == Math.floor(duration)) {
                        clearInterval(timer);
                        total_s = 0;
                        m = "0" + 0;
                        s = "0" + 0;

                        play_button.innerHTML = 
                            `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                            </svg>`
                    }

                    progress_bar_value = (total_s / Math.floor(duration)) * 100;
                    document.getElementById("audio_progress").value = progress_bar_value;

                    putValue();
                }, 1000);
            }
            
        }

        function pauseAudio() {
            audio.pause()
            isSongPlaying = false;
            play_button.innerHTML = 
                `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                </svg>`
            clearInterval(timer);
        }

        async function getAudioDuration(file) {
            const url = URL.createObjectURL(file);

            return new Promise((resolve) => {
                const audio = document.createElement("audio");
                audio.muted = true;
                const source = document.createElement("source");
                source.src = url;

                audio.preload = "metadata";
                audio.appendChild(source);
                audio.onloadedmetadata = function() {
                    resolve(audio.duration);
                }
            })
        }

        // Cookies
        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }
    </script>
</html>