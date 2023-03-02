import "./Login.css";
import React from "react";
import axios from "axios";
import { useNavigate } from 'react-router-dom';

export default function Login(){
    const navigate = useNavigate();
    const loginto = async() => {
        var data = new FormData();
        data.append("username", document.getElementById('username').value);
        data.append("password", document.getElementById('password').value);

        await axios.post(
            'http://localhost:4000/login', data, {withCredentials : true}
        ).then(response => {
            console.log(response);
            localStorage.setItem("user", JSON.stringify(response.data.user));
            if(response.data.user.isAdmin){
                navigate('/subscription-list')
            }else{
                navigate('/manage-songs')
            }
        }).catch(err => {
            console.log(err);
            if(err.response.status === 403){
                alert("Wrong password")
            }else if(err.response.status === 405){
                alert("Username is not registered")
            }
        })

    }

    
    document.title="Login - Binotify";

    return (
        <div className="flex flex-col items-center text-black">
            <header className="border-b border-black pb-6 w-full">
                <img className="mx-auto pt-4 w-1/8" src="/../../src/assets/binotify.svg" alt="binotify"/>
            </header>
            <div className="justify-center flex flex-col items-center w-1/2">
                <div className="label-input-group py-4">
                    <label className="w-full font-bold text-md pb-2">Username</label>
                    <input className="login-register-input" type="text" placeholder="Username" id="username" required/>
                </div>
                <div className="label-input-group pb-4">
                    <label className="w-full font-bold text-md pb-2">Password</label>
                    <input className="login-register-input" type="password" placeholder="Password" id="password" required/>
                </div>
                <div onClick={() => loginto()} className="pb-8">
                    <button className="login-register-button">LOG IN</button>
                </div>
                <hr className="w-1/2 text-center pb-8"/>
                <p className="text-center font-semibold pb-4">Don't have an account?</p>
                <a href="/register">
                    <button className="border-slate-400 hover:border-slate-600 signup">SIGN UP FOR BINOTIFY</button>
                </a>
            </div>
        </div>
    )
}