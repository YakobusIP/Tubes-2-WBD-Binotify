<?php
    namespace Client\pages\AddAlbum;
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
    <link rel="stylesheet" href="/../../Client/pages/AddAlbum/AddAlbum.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <title>Binotify - Add Album - Admin</title>
</head>
<body>
    <div id="split_page">
        <?php include "Client/components/NavbarAdmin/sidebar_admin.php"; ?>
        <main id="main_body">
            <?php include "Client/components/NavbarAdmin/topbar_admin.php"; ?>
            <section id="add_album_section">
                <!-- page here  -->
                <div class="content">
                    <h1 class="header">ADD ALBUM</h1>
                    <hr>
                    <div class="form-container">
                        <div class="form-item">
                            <div class="form-label"><h2>Judul</h2></div>
                            <div class="form-input">
                                <input id="addJudul" type="text" placeholder="Judul Album" required>
                            </div>         
                        </div>
                        <div class="form-item">
                            <div class="form-label"><h2>Penyanyi</h2></div>
                            <div class="form-input">
                                <input id="addPenyanyi" type="text" placeholder="Nama Penyanyi" required>
                            </div>         
                        </div>
                        <div class="form-item">
                            <div class="form-label"><h2>Genre</h2></div>
                            <div class="form-input">
                                <input id="addGenre" type="text" placeholder="Genre Album" required>
                            </div>         
                        </div>
                        <div class="form-item">
                            <div class="form-label"><h2>Tanggal Terbit</h2></div>
                            <div class="form-input">
                                <input id="addTanggal" type="date" required>
                            </div>         
                        </div>
                        <div class="form-item">
                            <div class="form-label"><h2>Album Cover</h2></div>
                            <div class="form-input">
                                <input id="addCover" type="file" accept="image/*" required>
                            </div>         
                        </div>
                        <div class="btn-container">
                            <div id="addAlbumBtn" onclick={addAlbumHandler(event)}>
                                Submit
                            </div>
                        </div>
                    </div>
                <div>
                <!-- page here  -->
            </section>
        </main>
    </div>
</body>
<script>
    const isAllBlankSpace = (string) => {
        for(let i = 0; i < string.length; i++) {
            if(string.charAt(i) != ' ') {
                return false;
            }
        }
        return true;
    };
    const addAlbumHandler = (e) => {
        e.preventDefault();
        const judul = document.getElementById('addJudul').value;
        const penyanyi = document.getElementById('addPenyanyi').value;
        const genre = document.getElementById('addGenre').value;
        const tanggal_terbit = document.getElementById('addTanggal').value;
        const image = document.getElementById('addCover').files[0];
        
        const data = new FormData();
        data.append("judul", judul);
        data.append("penyanyi", penyanyi);
        data.append("genre", genre);
        data.append("tanggal_terbit", tanggal_terbit);
        data.append("image", image);

        if(
            isAllBlankSpace(judul) ||
            isAllBlankSpace(penyanyi) ||
            isAllBlankSpace(genre) ||
            isAllBlankSpace(tanggal_terbit) ||
            typeof image === "undefined"
        ){
            alert("All fields must be filled!");
        } else {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/add-album");
            xhr.onload = () => {
                console.log('DONE: ', xhr.status);
                if(xhr.status===201){
                    const response = JSON.parse(xhr.response);
                    const album_id = response['album_id'];
                    const params = "id="+album_id;
                    console.log(response);
                    console.log(album_id);
                    window.location.href="/detail-album?"+params;
                };
            };
            xhr.send(data);
        }
    };
</script>
</html>