<?php
    namespace Client\pages\Register;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../../Client/pages/Register/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <title>Register - Binotify</title>
    <link rel="icon" type="image/x-icon" href="Client/components/binotify-small.svg">
</head>
<body>
    <h1><img src="../../Client/components/binotify.svg" alt="binotify"></h1>
    <h1>Sign up for free to start<br>listening.</h1>
    <form>
        <div class="email-username-group" id="email-card">
            <label for="email" class="email-username-short">What's your email?</label>
            <!-- onfocusout={setEmailBox(event)} -->
            <input type="text" placeholder="Enter your email" autocapitalize="off" onfocusout={processEmail()} id="email">
        </div>
        <div class="email-username-group">
            <label for="full_name" class="email-username-short">What's your name?</label>
            <input type="text" placeholder="Enter your full name" autocapitalize="off" id="full_name">
        </div>
        <div class="email-username-group" id="username-card">
            <label for="username" class="email-username">What's your username?</label>
            <!-- onfocusout={setUsernameBox(event)} -->
            <input type="text" placeholder="Enter a username" autocapitalize="off" onfocusout={processUsername()}  id="username">
        </div>
        <div class="email-username-group">
            <label for="password" class="email-username-short">Create a password</label>
            <input type="password" placeholder="Create a password" autocapitalize="off" id="password">
        </div>
        <div class="email-username-group">
            <label for="passwordConfirm" class="email-username">Confirm your password</label>
            <input type="password" placeholder="Enter your password again" autocapitalize="off" id="passwordConfirm">
        </div>
        <p class="footer">By clicking on sign-up, you agree to Binotify's Terms and Conditions of Use.</p>
        <p class="footer">To learn more about how Binotify collects, uses, shares and protects your <br>personal data, please see Binottify's Privacy Policy.</p>
        <div>
            <button class="login" onclick={register(event)}>REGISTER</button>
        </div>
    </form>
    <p>Have an account? <a href="/login">Log in</a>.</p>
</body>

<script>
    let emailAvailable = true;
    let usernameAvailable = true;

    function remove(e){
        var element = e.target;
        var elementalert = document.getElementById("alert")
        element.remove()
        elementalert.remove()
    }

    function sleep(ms){
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function debounce(func, timeout = 300){
        let timer;

        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args)
            }, timeout);
        };
    }

    const setEmailBox = () =>{
        const text = document.getElementById('email').value;
        console.log(text);
        const xhr = new XMLHttpRequest();

        xhr.onload = () =>{
            if(xhr.status===200){
                console.log(xhr.response);
                console.log(xhr.response.length);
                if (text.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/) && xhr.response.length<14){
                    emailAvailable=true;
                    document.getElementById('email').style.border="2px solid green";
                }else{
                    emailAvailable=false;
                    document.getElementById('email').style.border="2px solid red";
                    var x = document.createElement("Label");
                    x.setAttribute("for", "error-msg");
                    x.setAttribute("id", "delElmnt");
                    if(!text.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
                        x.innerHTML = "Email is invalid";
                    }else{
                        x.innerHTML = "Email is already registered";
                    }
                    const pdiv = document.getElementById('email-card');
                    pdiv.appendChild(x);
                    console.log("masuk");
                    sleep(1000).then(() => { deleteMessage("delElmnt"); });
                }
            }
        }

        xhr.open('POST', '/register/search-email');
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`email=${text}`);
    }

    const setUsernameBox = () =>{
        const text = document.getElementById('username').value;
        const xhr = new XMLHttpRequest();
        xhr.onload = () =>{
            if(xhr.status===200){
                response = JSON.parse(xhr.response);
                console.log(response);
                if (text.match(/^([a-zA-Z0-9_]+)$/) && response.result.length === 0){
                    usernameAvailable=true;
                    document.getElementById('username').style.border="2px solid green";
                }else{
                    usernameAvailable=false;
                    document.getElementById('username').style.border="2px solid red";
                    var x = document.createElement("Label");
                    x.setAttribute("for", "error-msg");
                    x.setAttribute("id", "delElmnt");
                    if(!text.match(/^([a-zA-Z0-9_]+)$/)){
                        x.innerHTML = "Username is invalid"
                    }else{
                        x.innerHTML = "Username is already registered"
                    }
                    const pdiv = document.getElementById('username-card');
                    pdiv.appendChild(x);
                    sleep(1000).then(() => { deleteMessage("delElmnt"); });
                }
            }
        }

        xhr.open('POST', '/register/search-username');
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`username=${text}`);
    }

    const processEmail = debounce(() => setEmailBox());
    const processUsername = debounce(() => setUsernameBox());

    function deleteMessage(idElmt){
        const delElmt = document.getElementById(idElmt);
        delElmt.remove()
    }


    const register = (e) => {
        e.preventDefault();
        
        if (emailAvailable===false) {
            alert("Email unavailable")
        } else if(usernameAvailable===false){
            alert("Username unavailable")
        } else{
            const email = document.getElementById('email').value;
            const full_name = document.getElementById('full_name').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById("passwordConfirm").value;
            console.log(full_name);
            const xmlhttp = new XMLHttpRequest();
            const xmlhttplogin = new XMLHttpRequest();
            var isRegistered = false;
            if(password != passwordConfirm){
                alert("Password doesn't match");
            }else{
                xmlhttp.onload = () =>{
                    console.log('DONE: ', xmlhttp.status);
                    if(xmlhttp.status===201){
                        isRegistered = true;
                    }   
                }

                xmlhttp.open("POST", "/register");
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(`email=${email}&full_name=${full_name}&username=${username}&password=${password}&passwordConfirm=${passwordConfirm}`);
                
                xmlhttplogin.onload = () => {
                    if(xmlhttplogin.status === 200){
                        window.location.href="/home"
                        document.cookie = "playedSong=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;"
                    }
                }

                xmlhttplogin.open("POST", "/login");
                xmlhttplogin.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttplogin.send(`username=${username}&password=${password}`);
            }

            
        }

        

    };
</script>
</html>