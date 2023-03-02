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
    <link rel="stylesheet" href="/../../Client/pages/DetailAlbumAdmin/DetailAlbumAdmin.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <title>Binotify - Album Detail - Admin</title>
</head>
<body>
    <div id="split_page">
        <?php include "Client/components/NavbarAdmin/sidebar_admin.php"; ?>
        <main id="main_body">
            <?php include "Client/components/NavbarAdmin/topbar_admin.php"; ?>
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
                    <div class="icon-action">
                        <div class="action-item action-1" onclick={editAlbumHandler(event)}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                        </div>
                        <div class="action-item action-2" onclick={deleteAlbumHandler(event)}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </div>
                        <div class="action-item action-3" onclick={addSongHandler(event)}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="action-item action-3" onclick={deleteSongHandler(event)}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
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
                </div>
                <!-- page here  -->
            </section>
        </main>
    </div>

    <!-- edit album modal -->
    <div class="edit-modal-container" id="editModalContainer">
        <div class="edit-modal">
            <h1>EDIT ALBUM</h1>
            <div class="modal-content">
                <div class="form-item">
                    <div class="form-label"><h3>Judul</h3></div>
                    <div class="form-input">
                        <input id="editJudul" type="text" value="<?= $album['judul'] ?>" required>
                    </div>         
                </div>
                <div class="form-item">
                    <div class="form-label"><h3>Penyanyi</h3></div>
                    <div class="form-input">
                        <input id="editPenyanyi" type="text" value="<?= $album['penyanyi'] ?>" readonly="readonly" disabled="disabled">
                    </div>         
                </div>
                <div class="form-item">
                    <div class="form-label"><h3>Genre</h3></div>
                    <div class="form-input">
                        <input id="editGenre" type="text" value="<?= $album['genre'] ?>" required>
                    </div>         
                </div>
                <div class="form-item">
                    <div class="form-label"><h3>Tanggal Terbit</h3></div>
                    <div class="form-input">
                        <input id="editTanggal" type="date" value="<?= $album['tanggal_terbit'] ?>" required>
                    </div>         
                </div>
                <div class="form-item">
                    <div class="form-label"><h3>Album Cover</h3></div>
                    <div class="form-input">
                        <input id="editCover" type="file" accept="image/*">
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
    <!-- edit album modal -->
    <!-- delete album modal -->
    <div class="delete-modal-container" id="deleteModalContainer">
        <div class="delete-modal">
            <h2>Are you sure to delete this album?</h2>
            <div class="choice">
                <div id="deleteYesBtn" onclick={yesDeleteHandler(event)}>
                    Yes
                </div>
                <div id="deleteCancelBtn" onclick={cancelDeleteHandler(event)}>
                    Cancel
                </div>
            </div>
        </div>
    </div>
    <!-- delete album modal -->
</body>
<script>
    const editAlbumHandler = (e) => {
        e.preventDefault();
        const modal_container = document.getElementById('editModalContainer');
        modal_container.classList.add('show');
    };
    const saveEditHandler = (e) => {
        e.preventDefault();
        const modal_container = document.getElementById('editModalContainer');
        modal_container.classList.remove('show');

        const album_id = <?= $album['album_id'] ?>;
        const judul = document.getElementById('editJudul').value;
        const genre = document.getElementById('editGenre').value;
        const tanggal_terbit = document.getElementById('editTanggal').value;
        const image = document.getElementById('editCover').files[0];
        const data = new FormData();
        data.append("album_id", album_id);
        data.append("judul", judul);
        data.append("genre", genre);
        data.append("tanggal_terbit", tanggal_terbit);
        data.append("image", image);

        const xhr = new XMLHttpRequest();
        const params = "id="+album_id;
        xhr.open("POST", "/detail-album/edit-album");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/detail-album?"+params;
            };
        };
        xhr.send(data);
    }
    const cancelEditHandler = (e) => {
        e.preventDefault();
        const modal_container = document.getElementById('editModalContainer');
        modal_container.classList.remove('show');
    }
    const deleteAlbumHandler = (e) => {
        e.preventDefault();
        const modal_container = document.getElementById('deleteModalContainer');
        modal_container.classList.add('show');
    };
    const yesDeleteHandler = (e) => {
        e.preventDefault();
        const modal_container = document.getElementById('deleteModalContainer');
        modal_container.classList.remove('show');
        
        console.log('Delete the album');
        console.log('Album ID: <?= $album['album_id']; ?>');

        const album_id = <?= $album['album_id']; ?>;
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/detail-album/delete-album");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/album-list";
            };
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`album_id=${album_id}`);
    }
    const cancelDeleteHandler = (e) => {
        e.preventDefault();
        const modal_container = document.getElementById('deleteModalContainer');
        modal_container.classList.remove('show');
    }
    const addSongHandler = (e) => {
        e.preventDefault();
        const albumId = <?= $album['album_id'] ?>;
        const penyanyi = "<?= $album['penyanyi'] ?>";
        const params = "id="+albumId +"&"+ "penyanyi="+penyanyi;
        console.log(params);

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "/detail-album/add-song");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/detail-album/add-song?"+params;
            }   
        }
        xhr.send(null);
    };
    const deleteSongHandler = (e) => {
        e.preventDefault();
        const albumId = <?= $album['album_id'] ?>;
        console.log("Album ID: " + albumId);

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "/detail-album/delete-song");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/detail-album/delete-song?id="+albumId;
            }   
        }
        xhr.send(null);
    };
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
