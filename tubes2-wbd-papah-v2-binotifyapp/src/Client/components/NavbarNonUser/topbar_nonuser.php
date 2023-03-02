<?php 
    namespace components\NavbarNonUser;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/../../Client/components/NavbarNonUser/navbar_nonuser.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    </head>
    <body>
        <div id="top_bar">
            <div id="search_div" onclick={location.href="/search?search_query=&genre=All&sort_by=judul&asc=true&page=1"}>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
                </svg>
                <input id="search_query" type="text" placeholder="What do you want to listen to?"/>
            </div>
            <div id="auth_button">
                <button onclick={location.href="/register"} id="signup_button">
                    <p>Sign up</p>
                </button>
                <button id="login_button" onclick={location.href="/login"}>
                    <p>Log in</p>
                </button>
            </div>
        </div>
    </body>
</html>