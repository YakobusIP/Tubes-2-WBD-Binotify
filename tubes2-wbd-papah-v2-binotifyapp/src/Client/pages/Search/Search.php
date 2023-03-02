<?php 
    namespace Client\pages\Search;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Client/pages/Search/Search.css">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotify - Search</title>
    </head>
    <body>
        <div id="split_page">
            <?php 
                if (!isset($_SESSION["isadmin"])){
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
                <div id="top_bar">
                    <div id="search_div">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
                        </svg>
                        <input id="search_query" type="text" placeholder="What do you want to listen to?" onkeyup={changeQuery()} />
                    </div>
                <?php if (!isset($_SESSION["isadmin"])): ?>
                    <div id="auth_button">
                        <button onclick={location.href="/register"} id="signup_button">
                            <p>Sign up</p>
                        </button>
                        <button id="login_button" onclick={location.href="/login"}>
                            <p>Log in</p>
                        </button>
                    </div>
                <?php elseif($_SESSION["isadmin"]): ?>
                    <div class="right-item">
                        <button id="user_button">
                            <span id="user_background">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <p>Admin</p>
                        </button>
                        <div class="logout-btn" onclick={location.href="/home/logout"}>
                            Logout
                        </div>
                    </div>
                <?php else: ?>
                    <div class="right-item">
                        <button id="user_button">
                            <span id="user_background">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <p><?php 
                                echo $_SESSION["username"];
                            ?></p>
                        </button>
                        <div class="logout-btn" onclick={location.href="/home/logout"}>
                            Logout
                        </div>
                    </div>
                <?php endif; ?> 
                </div>
                <section id="search_body">
                    <h1>SEARCH RESULTS</h1>
                    <select id="genre_option" onchange={changeGenre()}>
                        <option value="All">All Genre</option>
                        <?php foreach($genre_list as $key=>$genre): ?>
                            <option value="<?= $genre["genre"]; ?>"><?= $genre["genre"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="result_div">
                        <table id="result_table">
                            <tr>
                                <th>No</th>
                                <th onclick={sortByTitle()}>Title</th>
                                <th onclick={sortByDate()}>Release Date</th>
                                <th onclick={sortByGenre()}>Genre</th>
                                <th onclick={sortByDuration()}>Duration</th>
                            </tr>
                            <?php foreach($search_result["data"] as $key=>$song): ?>
                                <tr id="<?= 'song_'.$song["song_id"]; ?>" onclick={detailSong(event)}>
                                    <td id="<?= 'detail1_'.$song["song_id"]; ?>"><?= $key + ($page - 1) * 5 + 1; ?></td>
                                    <td id="<?= 'detail2_'.$song["song_id"]; ?>">
                                        <div id="<?= 'detail21_'.$song["song_id"]; ?>">
                                            <img id="<?= 'detail211_'.$song["song_id"]; ?>" src="<?= $song["image_path"] ?>" alt="Song Image" />
                                            <div id="<?= 'detail212_'.$song["song_id"]; ?>">
                                                <p id="<?= 'detail2121_'.$song["song_id"]; ?>"><?= $song["judul"]; ?></p>
                                                <p id="<?= 'detail2122_'.$song["song_id"]; ?>"><?= $song["penyanyi"]; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="<?= 'detail3_'.$song["song_id"]; ?>"><?= $song["tanggal_terbit"]; ?></td>
                                    <td id="<?= 'detail4_'.$song["song_id"]; ?>"><?= $song["genre"]; ?></td>
                                    <td id="<?= 'detail5_'.$song["song_id"]; ?>">
                                        <?php  
                                            $seconds = $song["duration"];
                                            $m = floor(floor($seconds / 60) % 60);
                                            $s = $seconds % 60;
                                            if ($m < 10) {
                                                echo "0".$m.":";
                                            } else {
                                                echo $m.":";
                                            }

                                            if ($s < 10) {
                                                echo "0".$s;
                                            } else {
                                                echo $s;
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div id="pagination">
                        <svg onclick={previousPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        <svg onclick={nextPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </section>
            </main>
        </div>
    </body>
    <script>
        var url = new URLSearchParams(window.location.search)
        var page = url.get('page');
        var pageCount = <?= $search_result["last_page"]; ?>;

        function debounce(func, timeout = 300) {
            let timer;

            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args)
                }, timeout);
            };
        }

        const changeGenre = () => {
            genreChosen = document.getElementById("genre_option").value;
            url.set('genre', genreChosen);
            window.location.search = url.toString();
        }

        const changeQuery = debounce(() => getResults());
        const getResults = () => {
            searchQuery = document.getElementById("search_query").value;
            url.set('search_query', searchQuery);
            window.location.search = url.toString();
        }

        var asc_title = url.get('asc');
        const sortByTitle = () => {
            sort_by = "judul";
            if (asc_title === "true") {
                asc_title = false;
            } else {
                asc_title = true;
            }
            var asc = asc_title;
            url.set('sort_by', sort_by);
            url.set('asc', asc);
            window.location.search = url.toString();
        }

        var asc_date = url.get('asc');
        const sortByDate = () => {
            sort_by = "tanggal_terbit";
            if (asc_date === "true") {
                asc_date = false;
            } else {
                asc_date = true;
            }
            var asc = asc_date;
            url.set('sort_by', sort_by);
            url.set('asc', asc);
            window.location.search = url.toString();
        }

        var asc_genre = url.get('asc');
        const sortByGenre = () => {
            sort_by = "genre";
            if (asc_genre === "true") {
                asc_genre = false;
            } else {
                asc_genre = true;
            }
            var asc = asc_genre;
            url.set('sort_by', sort_by);
            url.set('asc', asc);
            window.location.search = url.toString();
        }

        var asc_duration = url.get('asc');
        const sortByDuration = () => {
            sort_by = "duration";
            if (asc_duration === "true") {
                asc_duration = false;
            } else {
                asc_duration = true;
            }
            var asc = asc_duration;
            url.set('sort_by', sort_by);
            url.set('asc', asc);
            window.location.search = url.toString();
        }

        const detailSong = (e) =>{
            e.preventDefault();
            console.log(e.target);
            const songId = (e.target.id).split('_')[1];

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/song-detail");
            xhr.onload = () => {
                if(xhr.status===200){
                    window.location.href = "/song-detail?id="+songId;
                }
            }
            xhr.send();
        };

        const nextPage = () => {
            console.log(pageCount);
            if (parseInt(page) !== pageCount && pageCount !== 0) {
                page++;
                url.set('page', page);
                window.location.search = url.toString();    
            }
        }

        const previousPage = () => {
            if (parseInt(page) !== 1 && pageCount !== 0) {
                page--;
                url.set('page', page);
                window.location.search = url.toString();
            }
        }

        window.onload = () => {
            var url = new URLSearchParams(window.location.search)
            var option = document.getElementsByTagName("option");
            for (i = 0; i < option.length; i++) {
                if (option[i].innerHTML === url.get('genre')) {
                    option[i].setAttribute("selected", "selected");
                }
            }

            var query = document.getElementById("search_query");
            query.value = url.get('search_query');
            query.focus();
        }
    </script>
</html>