<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/Client/pages/AddSongs/AddSongs.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotify - Add Songs - Admin</title>
    </head>
    <body>
        <div id="split_page">
            <?php include "Client/components/NavbarAdmin/sidebar_admin.php"; ?>
            <main id="main_body">
                <?php include "Client/components/NavbarAdmin/topbar_admin.php"; ?>
                <section id="add_song_section">
                    <!-- page here  -->
                    <div class="content">
                        <h1 class="header">ADD SONG</h1>
                        <hr>
                        <div class="form-container">
                            <div class="form-item">
                                <div class="form-label"><h2>Album</h2></div>
                                <select class="form-select" id="create_album">
                                    <option value="No Album">No Album</option>
                                    <?php foreach($album_list as $key=>$album): ?>
                                        <option value="<?= $album["album_id"]; ?>"><?= $album["judul"]; ?></option>
                                    <?php endforeach; ?>
                                </select>     
                            </div>
                            <div class="form-item">
                                <div class="form-label"><h2>Genre</h2></div>
                                <div class="form-input">
                                    <input id="create_genre" type="text" placeholder="Genre" required>
                                </div>         
                            </div>
                            <div class="form-item">
                                <div class="form-label"><h2>Title</h2></div>
                                <div class="form-input">
                                    <input id="create_title" type="text" placeholder="Title" required>
                                </div>         
                            </div>
                            <div class="form-item">
                                <div class="form-label"><h2>Singer</h2></div>
                                <div class="form-input">
                                    <input id="create_singer" type="text" placeholder="Singer" required>
                                </div>         
                            </div>
                            <div class="form-item">
                                <div class="form-label"><h2>Release Date</h2></div>
                                <div class="form-input">
                                    <input id="create_date" type="date" required>
                                </div>         
                            </div>
                            <div class="form-item">
                                <div class="form-label"><h2>Song Cover</h2></div>
                                <div class="form-input">
                                    <input id="create_image" type="file" accept="image/*" required>
                                </div>         
                            </div>
                            <div class="form-item">
                                <div class="form-label"><h2>Audio File</h2></div>
                                <div class="form-input">
                                    <input id="create_audio" type="file" accept="audio/*" required>
                                </div>         
                            </div>
                            <div class="btn-container">
                                <div id="add_song" onclick={addsong(event)}>
                                    Submit
                                </div>
                            </div>
                        </div>
                    <div>
                </section>
            </main>
        </div>
    </body>


    <script>
        const addsong = async (e) => {
            e.preventDefault();
            var album = document.getElementById('create_album').value;
            if (album === "No Album") {
                album = 0;
            }
            const genre = document.getElementById('create_genre').value;
            const title = document.getElementById('create_title').value;
            const singer = document.getElementById('create_singer').value;
            const date = document.getElementById('create_date').value;
            const image = document.getElementById('create_image').files[0];
            const audio = document.getElementById('create_audio').files[0];
            const duration = await getAudioDuration(audio);

            const formData = new FormData();
            formData.append("album", album);
            formData.append("genre", genre);
            formData.append("title", title);
            formData.append("singer", singer);
            formData.append("date", date);
            formData.append("duration", duration);
            formData.append("image", image);
            formData.append("audio", audio);

            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4) {
                    const data = JSON.parse(this.response);
                    alert(data.message);
                    const song_id = data.song_id;
                    window.location.href = '/song-detail?id=' + song_id;
                }
            }

            xmlhttp.open("POST", "/add-song/create-song");
            xmlhttp.send(formData);
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
        

    </script>
</html>