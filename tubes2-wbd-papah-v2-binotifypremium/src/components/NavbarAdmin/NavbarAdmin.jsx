import { useNavigate } from 'react-router-dom';
import axios from "axios";

function NavbarAdmin() {
    const navigate = useNavigate()
    const logout = async() =>{
        await axios.delete(
            'http://localhost:4000/logout', {withCredentials : true}
        ).then(response =>{
            localStorage.removeItem("user");
            navigate('/login');
        }).catch(err =>{
            console.log(err)
        })
    }

    return (
        <nav className="sticky top-0 flex flex-row bg-main-black-2 justify-between items-center px-4 py-2 font-main box-border">
            <img className="w-1/10 box-content" src="../src/assets/binotify-white.svg" alt="Logo"/>
            <div className="flex flex-row m-2 justify-end box-content">
                <button className="flex flex-row items-center p-0 mr-4 rounded-full bg-black border-bg-black hover:bg-main-black-3 hover:border-main-black-3">
                    <span className="flex h-8 w-8 items-center justify-center rounded-full bg-main-black-1 cursor-pointer ease-in duration-100 border-main-black-1 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </span>
                    <p className="font-bold text-white m-0 text-md pl-1 pr-2">Admin</p>
                </button>
                <div onClick={() => logout()} className="flex appearance-none rounded-md items-center justify-center bg-main-red text-white outline-none hover:border-main-red font-bold px-4 cursor-pointer">
                    Logout
                </div>
                {/* <button className="flex appearance-none rounded-md items-center justify-center bg-main-red text-white outline-none hover:border-main-red font-bold px-4 cursor-pointer">
                    Logout
                </button> */}
            </div>
        </nav>
    );
}

export default NavbarAdmin;
