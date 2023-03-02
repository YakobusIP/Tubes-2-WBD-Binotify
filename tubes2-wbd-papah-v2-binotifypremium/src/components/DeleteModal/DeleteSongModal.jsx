import React from 'react'
import axios from 'axios'

const DeleteSongModal = ({song_id, setShowModalDelete, token}) => {
    const deleteSong = async () => {
        var data = new FormData();
        data.append("song_id", song_id);
        
        await axios.delete(
            `http://localhost:4000/delete-song/${song_id}`, {
                headers: {
                    "Authorization" : "Bearer " + token
                }
            }
        ).then(response => {
            console.log(response);
        }).catch(err => {
            console.log(err);
        })

        setShowModalDelete(false);
        window.location.reload();
    }

    return (
        <div className="relative z-10">
            <div className="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
            <div className="fixed z-10 inset-0">
                <div className="flex items-center justify-center min-h-screen">
                    <div className="relative flex flex-col items-center text-white justify-center md:w-2/5 w-1/3 bg-main-black-2 rounded-lg shadow-xl transform transition-all max-w-full p-4 font-main outline outline-main-green outline-2">
                        <h1 className="text-2xl py-2 font-bold text-center">Are you sure you want to delete this song?</h1>
                        <div className="flex flex-col justify-center items-center w-full px-4">
                            <div className="flex justify-center w-full">
                                <div onClick={() => deleteSong()} className="modal-green-btn w-1/3 ease-in-out duration-500 hover:w-2/5">Delete</div>
                                <div onClick={() => setShowModalDelete(false)} className="modal-red-btn w-1/3 ease-in-out duration-500 hover:w-2/5">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default DeleteSongModal