import React from "react"
import axios from "axios";

const AddSongModal = ({user_id, setShowModalAdd, token}) => {
    const addSong = async () => {
        var data = new FormData();
        data.append("judul", document.getElementById('new_title').value);
        data.append("penyanyi_id", user_id);
        data.append("audio_file", document.getElementById('new_audio').files[0]);

        console.log(token)

        await axios.post(
            'http://localhost:4000/add-song', data, {
                headers: {
                    "Authorization" : "Bearer " + token
                }
            }
        ).then(response => {
            console.log(response);
        }).catch(err => {
            console.log(err);
        })

        setShowModalAdd(false);
        window.location.reload();
    }

    return (
        <div className="relative z-10">
            <div className="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
            <div className="fixed z-10 inset-0">
                <div className="flex items-center justify-center min-h-screen">
                    <div className="relative flex flex-col items-center text-white justify-center md:w-2/5 w-1/3 bg-main-black-2 rounded-lg shadow-xl transform transition-all max-w-full p-4 font-main outline outline-main-green outline-2">
                        <h1 className="text-4xl py-2 font-bold">ADD SONG</h1>
                        <div className="flex flex-col justify-center items-center w-full px-4">
                            <div className="flex items-center w-full p-2">
                                <div className="flex justify-end w-2/5 font-bold px-4 text-xl">Judul</div>
                                <div className="flex w-3/5 text-black px-4">
                                    <input 
                                    className="w-4/5 h-8 px-2 rounded-md ease-in-out duration-500 focus:outline focus:outline-main-green focus:outline-2 focus:bg-main-black-2 focus:text-main-green" 
                                    id="new_title" 
                                    type="text" 
                                    placeholder="Judul"
                                    required/>
                                </div>
                            </div>
                            <div className="flex items-center w-full p-2">
                                <div className="flex justify-end w-2/5 font-bold px-4 text-xl">Audio File</div>
                                <div className="flex w-3/5 text-white px-4">
                                    <input 
                                    className="w-4/5 h-8" 
                                    id="new_audio" 
                                    name="audio_file"
                                    type="file" 
                                    accept="audio/*" 
                                    required/>
                                </div>
                            </div>
                            <div className="flex justify-center w-full">
                                <div onClick={() => addSong()} className="modal-green-btn w-1/3 ease-in-out duration-500 hover:w-2/5">Save</div>
                                <div onClick={() => setShowModalAdd(false)} className="modal-red-btn w-1/3 ease-in-out duration-500 hover:w-2/5">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default AddSongModal;