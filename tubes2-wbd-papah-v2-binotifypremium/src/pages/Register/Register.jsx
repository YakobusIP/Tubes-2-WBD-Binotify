import "./Register.css";
import axios from "axios";
import { useState } from "react";
import { useNavigate } from 'react-router-dom';

export default function Register(){
    const navigate = useNavigate();
    var emailValid = false;
    var unameValid = false;
    var filled = true;
    const addUser = async() => {
        var data = new FormData();
        var users = new FormData();

        console.log(isValidUname("admin"));
        console.log(document.getElementById('username'));

        if(document.getElementById('email').value !== ""){
            data.append("email", document.getElementById('email').value);
        }else{
            filled = false;
            alert("Email is empty");
        }

        if(!isValidEmail(document.getElementById('email').value)){
            filled = false;
            alert("Email is not valid");
        }

        if(document.getElementById('username').value !== ""){
            data.append("username", document.getElementById('username').value);
            users.append("username", document.getElementById('username').value);
        }else{
            filled = false;
            alert("Username is empty");
        }

        if(!isValidUname(document.getElementById('username').value)){
            filled = false;
            alert("Username is not valid");
        }

        if(document.getElementById('password').value !== ""){
            users.append("password", document.getElementById('password').value);
            data.append("password", document.getElementById('password').value)
        }else{
            filled = false;
            alert("Password is empty")
        }

        if(document.getElementById('full_name').value !== ""){
            data.append("name", document.getElementById('full_name').value)
        }else{
            filled = false;
            alert("Name is empty")
        }


        if(filled){
            var isRegistered = false;
    
            await axios.post(
                'http://localhost:4000/register', data
            ).then(response => {
                isRegistered = true;
            }).catch(err=>{
                console.log(err);
                if(err.response.status===405){
                    alert("Username is already taken");
                }else if(err.response.status === 406){
                    alert("Email is already used");
                }
            })
    
            if(isRegistered){
                await axios.post(
                    'http://localhost:4000/login', users, {withCredentials : true}
                ).then(response=>{
                    console.log("masuk")
                    localStorage.setItem("user", JSON.stringify(response.data.user));
                    navigate('/manage-songs')
                }).catch(err =>{
                    console.log(err);
                })
            }
        }
    }
    
    const [message, setMessage] = useState('');
    const [message2, setMessage2] = useState('');
    const [error, setError] = useState(null);
    const [error2, setError2] = useState(null);

    function isValidEmail(email){
        return /\S+@\S+\.\S+/.test(email);
    }

    function isValidUname(username){
        return /^([a-zA-Z0-9_]+)$/.test(username);
    }

    const handleChangeEmail = event => {
        if(!isValidEmail(event.target.value)){
            setError('Email invalid');
            document.getElementById('email').classList.remove("border-slate-400")
            document.getElementById('email').classList.remove("border-main-green")
            document.getElementById('email').classList.add("border-main-red")
        }else{
            setError(null);
            emailValid = true;
            document.getElementById('email').classList.remove("border-main-red")
            document.getElementById('email').classList.add("border-main-green")
        }

        setMessage(event.target.value);
    }

    const handleChangeUname = event => {
        if(!isValidUname(event.target.value)){
            setError2('Username invalid');
            document.getElementById('username').classList.remove("border-slate-400")
            document.getElementById('username').classList.remove("border-main-green")
            document.getElementById('username').classList.add("border-main-red")
        }else{
            setError2(null);
            unameValid = true;
            
            document.getElementById('username').classList.remove("border-main-red")
            document.getElementById('username').classList.add("border-main-green")
        }

        setMessage2(event.target.value);
    }

    document.title="Register - Binotify";
    
    return (
        <div className="flex flex-col items-center text-black">
            <header className="border-black pb-6 w-full">
                <img className="mx-auto pt-4 w-1/8 md:w-24 lg:w-32" src="/../../src/assets/binotify.svg" alt="binotify"/>
            </header>
            <h1 className="header-name pt-4 pb-10 text-center text-3xl font-semibold">Sign up for free to meet<br/>your fans.</h1>
            <div className="justify-center flex flex-col items-center w-1/2">
                <div className="label-input-group pb-4" id="email-card">
                    <label className="w-full font-bold text-md pb-2 ">What's your email?</label>
                    <input className="login-register-input border-slate-400" type="text" placeholder="Enter your email" id="email" name="email" value={message} onChange={handleChangeEmail} required/>

                    {error && <p style={{color:'red'}}>{error}</p>}
                </div>
                <div className="label-input-group pb-4">
                    <label className="w-full font-bold text-md pb-2">What's your name?</label>
                    <input className="login-register-input border-slate-400" type="text" placeholder="Enter your full name" id="full_name" name="full_name" required/>
                </div>
                <div className="label-input-group pb-4" id="username-card">
                    <label className="w-full font-bold text-md pb-2">What's your username?</label>
                    <input className="login-register-input border-slate-400" type="text" placeholder="Enter a username" id="username" name="username" value={message2} onChange={handleChangeUname} required/>
                
                    {error2 && <p style={{color:'red'}}>{error2}</p>}
                </div>
                <div className="label-input-group pb-4">
                    <label className="w-full font-bold text-md pb-2">Create a password</label>
                    <input className="login-register-input border-slate-400" type="password" placeholder="Create a password" id="password" name="password" required/>
                </div>
                <p className="footer">By clicking on register, you agree to Binotify's Terms and Conditions of Use.</p>
                <p className="footer pt-5 pb-5">To learn more about how Binotify collects, uses, shares and protects your <br/>personal data, please see Binottify's Privacy Policy.</p>
                <div onClick={() => addUser()}>
                    <button className="login-register-button mb-8">REGISTER</button>
                </div>
            </div>
            <p className="pb-10">Have an account? <a href="/login" className="login-text underline hover:decoration-green-300">Log in</a>.</p>
        </div>
    )
}