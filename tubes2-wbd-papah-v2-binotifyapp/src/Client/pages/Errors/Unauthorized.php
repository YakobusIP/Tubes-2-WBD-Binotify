<?php 
    namespace Client\pages\Errors;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Client/pages/Errors/Errors.css">
        <link rel="icon" type="image/x-icon" href="../../Client/components/binotify-common.svg">
        <title>Binotify - Unauthorized</title>
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
                    <h1 class="unauthorized">UNAUTHORIZED</h1>
                </section>
            </main>
        </div>
    </body>
</html>