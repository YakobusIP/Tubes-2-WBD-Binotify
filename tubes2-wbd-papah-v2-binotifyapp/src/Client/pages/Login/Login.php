<?php
    namespace Client\pages\Login;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../Client/pages/Login/Login.css">
    <title>Login - Binotify</title>
    <link rel="icon" type="image/x-icon" href="Client/components/binotify-small.svg">
</head>
<body>
    <h1><img src="/../../Client/components/binotify.svg" alt="binotify"></h1>
    <form>
        <div class="email-username-group">
            <label for="email-username" class="email-username">Username</label>
            <input type="text" placeholder="Username" autocapitalize="off" id="username">
        </div>
        <div class="email-username-group">
            <label for="pass" class="email-username">Password</label>
            <input type="password" placeholder="Password" autocapitalize="off" id="password">
        </div>
        <div>
            <button class="login" onclick={login(event)} >LOG IN</button>
        </div>
    </form>
    <hr class="garis tengah">
    <p>Don't have an account?</p>
    <button class="signup" onclick={location.href="/register"} >SIGN UP FOR BINOTIFY</button>
</body>

<script>
    const login = (e) => {
        e.preventDefault(); // biar kalo tombol diklik ga refresh
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onload = () => {
            console.log('DONE: ', xmlhttp.status);
            if(xmlhttp.status===200){
                window.location.href="/home"
                document.cookie = "playedSong=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;"
            }else if(xmlhttp.status === 400){
                alert("Empty Field");
            }else if(xmlhttp.status === 401){
                alert("Username doesn't exists");
            }
            else if(xmlhttp.status===402){
                alert("Password is invalid.");
            } else{
                alert("Invalid password")
            }
        };

        xmlhttp.open("POST", "/login");
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(`username=${username}&password=${password}`);
    };
</script>
</html>