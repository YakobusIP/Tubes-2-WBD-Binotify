import NavbarUser from "../../components/NavbarUser/NavbarUser";
import axios from "axios";
import { useEffect, useState } from "react";
import { useNavigate, useParams } from 'react-router-dom';
import AddSongModal from "../../components/AddSongModal/AddSongModal";
import EditSongModal from "../../components/EditSongModal/EditSongModal";
import DeleteSongModal from "../../components/DeleteModal/DeleteSongModal";

function ManageSongs() {
    const [showModalEdit, setShowModalEdit] = useState(false);
    const [showModalAdd, setShowModalAdd] = useState(false);
    const [showModalDelete, setShowModalDelete] = useState(false);
    const [premiumSongs, setPremiumSongs] = useState();
    const [page, setPage] = useState(1);
    const [pageCount, setPageCount] = useState(0);
    const [userData, setUserData] = useState();

    // Edit and Delete states
    const [editID, setEditID]= useState(0);
    const [deleteID, setDeleteID] = useState(0);
    const [curTitle, setCurTitle] = useState("");

    const navigate = useNavigate();

    const previousPage = () => {
        if (page !== 1) {
            setPage(page - 1);
            
        }
    }

    const nextPage = () => {
        if (page !== pageCount) {
            setPage(page + 1);
            
        }
    }

    const getPremiumSongs = async () => {
        await axios.get(
            `http://localhost:4000/song-list/${userData.user_id}/${page}`, {
                headers: {
                    "Authorization" : "Bearer " + getCookie("token")
                }
            }
        ).then(response => {
            setPremiumSongs(response.data.songs);
            setPageCount(response.data.pageCount);
        }).catch(err => {
            if (err.response.status === 403) {
                navigate('/login');
            }
            
        });
    };

    const logout = async() =>{
        await axios.delete(
            'http://localhost:4000/logout', {withCredentials : true}
        ).then(response =>{
            localStorage.removeItem("user");
        }).catch(err =>{
            console.log(err)
        })
    }

    useEffect(() => {
        const user = localStorage.getItem("user");
        if (user === null) {
            navigate('/login');
        } else {
            const parseUser = JSON.parse(user);
            if (parseUser.isAdmin){
                navigate('/');
                logout();
            } else{
                setUserData(parseUser);
            }
        }
        document.title = "Manage Songs";
    }, [page]);

    useEffect(() => {
        getPremiumSongs();
    }, [userData]);

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    return (
        <div className="bg-main-black-2 min-h-screen w-100vw font-main">
            <NavbarUser />
            {
                showModalAdd ? (
                    <AddSongModal user_id={userData?.user_id} setShowModalAdd={setShowModalAdd} token={getCookie("token")} />
                ) : null
            }
            {
                showModalEdit ? (
                    <EditSongModal song_id={editID} setShowModalEdit={setShowModalEdit} curTitle={curTitle} token={getCookie("token")} />
                ) : null
            }
            {
                showModalDelete ? (
                    <DeleteSongModal song_id={deleteID} setShowModalDelete={setShowModalDelete} token={getCookie("token")} />
                ) : null
            }
            <h1 className="text-center text-white font-bold py-8 text-6xl">{userData?.username}'s Songs</h1>
            <div className="flex flex-col items-center">
                <div className="flex flex-row w-3/4 items-center rounded-md py-2 px-8 text-white">
                    <h3 className="text-center text-xl font-bold w-1/6 cursor-default">#</h3>
                    <h3 className="text-xl font-bold w-3/5 cursor-default">Title</h3>
                    <h3 className="text-center text-xl font-bold w-1/4 cursor-default">Manage</h3>
                </div>
                <hr className="w-3/4"/>
                {
                    premiumSongs?.length >= 1 ?
                        premiumSongs?.map((songs, index) => {
                            return (
                                <div className="flex flex-col w-3/4 items-center">
                                    <div key={songs.song_id} className="flex w-full items-center py-4 px-8 text-white ease-in duration-100 hover:bg-main-black-1">
                                        <h3 className="text-center text-xl font-bold w-1/6 cursor-default">{(page - 1) * 5 + index + 1}</h3>
                                        <h3 className="text-xl font-bold w-3/5 cursor-default">{songs.judul}</h3>
                                        <div className="flex flex-row items-center justify-center w-1/4">
                                            <div className="px-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="w-8 h-8 hover:text-main-green cursor-pointer" onClick={() => {setShowModalEdit(true); setEditID(songs.song_id); setCurTitle(songs.judul)}}>
                                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                                                </svg>
                                            </div>
                                            <div className="px-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="w-8 h-8 hover:text-main-red cursor-pointer" onClick={() => {setShowModalDelete(true); setDeleteID(songs.song_id)}}>
                                                    <path fillRule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                        </div> 
                                        
                                    </div>
                                    <hr className="w-full"/>
                                </div>
                            )
                        }) :
                        <p>NO DATA</p>
                }
                
                <button className="green-btn my-4 w-1/2 bg-white text-black duration-200 ease-out hover:bg-main-green hover:border-main-green appearance-none hover:w-3/4" onClick={() => setShowModalAdd(true)}>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="w-6 h-6">
                        <path fillRule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clipRule="evenodd" />
                    </svg>
                </button>
                <div className="flex flex-row items-center justify-center gap-12">
                    <svg id="prev_page" onClick={() => previousPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-8 h-8 duration-200 cursor-pointer hover:text-main-green hover:w-10 hover:h-10">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>

                    <svg id="next_page" onClick={() => nextPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-8 h-8 duration-200 cursor-pointer hover:text-main-green hover:w-10 hover:h-10">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        </div>
    );
}

export default ManageSongs;