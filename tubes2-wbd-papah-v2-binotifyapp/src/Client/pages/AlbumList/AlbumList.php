<?php
    namespace Client\pages\AlbumList;
    /* @var $page */
    /* @var $num_start */
    /* @var $max_page */
    /* @var $album_list */
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
    <link rel="stylesheet" href="/../..//Client/pages/AlbumList/AlbumList.css">
    <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
    <title>Binotify - Album List</title>
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
            <section id="album_list_section">
                <!-- page here -->
                <div class="header">
                    <h1>ALBUM LIST</h1>
                </div>
                <div class="album-list">
                    <div class="album-header">
                        <div class="item-1">
                            <h3> # </h3>
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

                    <?php if (empty($album_list)): ?>
                        <h2>- There is no album -</h2>
                        <hr/>
                    <?php endif; ?>

                    <?php foreach ($album_list as $key=>$album): ?>
                    <div class="album-item" id="<?= 'album_'.$album['album_id']; ?>" onclick={detailAlbum(event)}>
                        <div class="item-1" id="<?= 'child1_'.$album['album_id']; ?>">
                            <h3 id="<?= 'child11_'.$album['album_id']; ?>"> <?= $num_start + $key; ?></h3>
                        </div>
                        <div class="item-2" id="<?= 'child2_'.$album['album_id']; ?>">
                            <h3 id="<?= 'child21_'.$album['album_id']; ?>"><?= $album['judul']; ?></h3>
                            <h4 id="<?= 'child22_'.$album['album_id']; ?>"><?= $album['penyanyi']; ?></h4>
                        </div>
                        <div class="item-3" id="<?= 'child3_'.$album['album_id']; ?>">
                            <h3 id="<?= 'child31_'.$album['album_id']; ?>"><?= $album['genre']; ?></h3>
                        </div>
                        <div class="item-3" id="<?= 'child4_'.$album['album_id']; ?>">
                            <h3 id="<?= 'child41_'.$album['album_id']; ?>"><?= date('Y', strtotime($album['tanggal_terbit'])); ?></h3>
                        </div>
                    </div>
                    <hr/>
                    <?php endforeach; ?>
                </div>  
                <div id="pagination">
                    <svg class="prev" onclick={previousPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <svg class="next" onclick={nextPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
                <!-- page here -->
            </section>
        </main>
    </div>
</body>
<script>
    const previousPage = () => {
        console.log('prev');
        const prevPage = <?= $page; ?> - 1;
        if (prevPage >= 0) {
            const params = "page="+prevPage
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/album-list");
            xhr.onload = () => {
                console.log('DONE: ', xhr.status);
                if(xhr.status===200){
                    prevPage == 0 ? window.location.href="/album-list"
                    : window.location.href="/album-list?" + params; 
                }   
            }
            xhr.send(null);
        }
    };
    const nextPage = () => {
        console.log('next');
        const nextPage = <?= $page; ?> + 1;
        const maxPage = <?= $max_page; ?>;
        if (nextPage < maxPage) {
            const params = "page="+nextPage;
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/album-list");
            xhr.onload = () => {
                console.log('DONE: ', xhr.status);
                if(xhr.status===200){
                    window.location.href="/album-list?" + params;
                }   
            }
            xhr.send(null);
        }
    };
    const detailAlbum = (e) => {
        e.preventDefault();
        const albumId = (e.target.id).split('_')[1];
        console.log("Album ID: " + albumId);

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "/detail-album");
        xhr.onload = () => {
            console.log('DONE: ', xhr.status);
            if(xhr.status===200){
                window.location.href="/detail-album?id="+albumId;
            }   
        }
        xhr.send(null);
    };
</script>
</html>
