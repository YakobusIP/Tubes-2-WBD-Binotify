import axios from "axios";
import { useEffect, useState } from "react";
import { useNavigate } from 'react-router-dom';
import NavbarAdmin from "../../components/NavbarAdmin/NavbarAdmin";
import "./SubscriptionList.css";

export default function SubscriptionList() {
    const navigate = useNavigate();
    // subsList index 
    // [0] => creator_id, [1] => subscriber_id, [2] => status
    const [subsList, setSubsList] = useState([]);
    const [listDisplay, setListDisplay] = useState([]);
    const [offset, setOffset] = useState(0);
    const [page, setPage] = useState(1);
    const [maxPage, setMaxPage] = useState(0);
    const [loading, setLoading] = useState(true);

    const logout = async () => {
        await axios.delete(
            'http://localhost:4000/logout', {withCredentials : true}
        ).then(response =>{
            localStorage.removeItem("user");
        }).catch(err =>{
            console.log(err)
        })
    }

    const previousPage = () => {
        if (page > 1) {
            setPage(page - 1);
            setOffset(offset - 5);
        }
        console.log("PREV PRESSED");
    }

    const nextPage = () => {
        if (page < maxPage) {
            setPage(page + 1);
            setOffset(offset + 5);
        }
        console.log("NEXT PRESSED");
    }

    const getRequestList = async () => {
        setLoading(true);
        const request = await axios.get("http://localhost:4000/get-subscription-list", {
            headers: {
                "Authorization" : "Bearer " + getCookie("token")
            }
        });
        const response = await request.data;
        setMaxPage(Math.ceil(response.length / 5));
        setSubsList(response);
        setListDisplay(response.slice(offset, offset + 5));
        setLoading(false);
    }

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

    const approveAction = async (e, key) => {
        e.preventDefault();
        const creator_id = document.getElementById("creator_id_" + key).innerHTML;
        const subscriber_id = document.getElementById("subscriber_id_" + key).innerHTML;
        
        console.log(e.target.innerHTML);
        console.log("Key: " + key);
        console.log("creator_id: " + creator_id);
        console.log("subscriber_id: " + subscriber_id);

        const data = {
            'creator_id': creator_id,
            'subscriber_id': subscriber_id,
        };
        const request = await axios.post(
            `http://localhost:4000/approve-request`, data, {
                headers: {
                "Authorization" : "Bearer " + getCookie("token")
            }
        }
        ).catch(err => {
            console.log(err);
        });
        const response = await request.data;
        console.log("APPROVAL RESPONSE");
        console.log(response);
        getRequestList();
    }

    const rejectAction = async (e, key) => {
        e.preventDefault();
        const creator_id = document.getElementById("creator_id_" + key).innerHTML;
        const subscriber_id = document.getElementById("subscriber_id_" + key).innerHTML;
        
        console.log(e.target.innerHTML);
        console.log("Key: " + key);
        console.log("creator_id: " + creator_id);
        console.log("subscriber_id: " + subscriber_id);

        const data = {
            'creator_id': creator_id,
            'subscriber_id': subscriber_id,
        };
        const request = await axios.post(
            `http://localhost:4000/reject-request`, data, {
                headers: {
                    "Authorization" : "Bearer " + getCookie("token")
                }
            }
        ).catch(err => {
            console.log(err);
        });
        const response = await request.data;
        console.log("REJECTION RESPONSE");
        console.log(response);
        getRequestList();
    }

    useEffect(() => {
        const user = localStorage.getItem("user");
        if (user === null) {
            navigate('/login');
        }
        else{
            const parsed = JSON.parse(user);
            if(parsed.isAdmin === false){
                navigate('/');
                logout();
            }
        }
        // go to SubscriptionList page
        getRequestList();
        document.title = 'Subscription List';
    }, [ page, offset ]);

    return (
        <div className="bg-main-black-2 min-h-screen w-100vw font-main">
            <NavbarAdmin />
            <div className="content flex flex-col font-main">
                <h1 className="text-5xl font-semibold my-5">Subscription List</h1>
                <table className="border-collapse mb-10 w-4/5">
                    <thead>
                        <tr className="border-b-2 border-white">
                            <th className="px-3 py-4 w-1/12">#</th>
                            <th className="px-3 py-4 w-3/12 text-left">Singer ID</th>
                            <th className="px-3 py-4 w-3/12 text-left">Subscriber ID</th>
                            <th className="px-3 py-4 w-5/12">Action</th>
                        </tr>
                    </thead>
                    {/* requests here */}
                    <tbody>
                    { loading ? (
                        <tr className="border-b-2 border-white">
                            <td></td>
                            <td className="px-3 py-4"><i>fetching data...</i></td>
                        </tr>
                    ) : (<>
                            { subsList.length == 0 ? (
                                <tr className="border-b-2 border-white">
                                    <td></td>
                                    <td></td>
                                    <td className="pl-3 py-4">THERE IS NO SUBSCRIPTION REQUEST</td>
                                </tr>
                            ) : (
                                <>
                                    { listDisplay.map((req, key) => {
                                        return (
                                            <tr key={ key } id={`rowSub`+key} className="row-table border-b-2 border-white">
                                                <td className="px-3 py-4 text-center">{ offset + key + 1 }</td>
                                                <td id={`creator_id_`+key} className="px-3 py-4">{ req[0] }</td>
                                                <td id={`subscriber_id_`+key} className="px-3 py-4">{ req[1] }</td>
                                                <td className="px-3 py-4">
                                                    <div className="h-full w-full flex justify-center items-center space-x-3">
                                                    { req[2] == 'PENDING' ? (
                                                        <>
                                                            <div onClick={(e) => approveAction(e, key)} className="action-btn py-1 bg-main-green w-2/5 text-center rounded">Approve</div>
                                                            <div onClick={(e) => rejectAction(e, key)} className="action-btn py-1 bg-main-red w-2/5 text-center rounded">Reject</div>
                                                        </>
                                                    ) : (
                                                        <>
                                                            {  req[2] == 'ACCEPTED' ? (
                                                                <div className="accepted-info py-1 w-4/5 text-center rounded">
                                                                    ACCEPTED
                                                                </div>
                                                            ) : (
                                                                <div className="rejected-info py-1 w-4/5 text-center rounded">
                                                                    REJECTED
                                                                </div>
                                                            )}
                                                        </>
                                                    )}
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    }) }
                                </>
                            )}
                    </>)}
                    </tbody>
                    {/* requests here */}
                </table>
                {/* pagination here */}
                <div className="mb-12 flex flex-row items-center justify-center gap-12">
                    <svg id="prev_page" onClick={() => previousPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-8 h-8 duration-200 cursor-pointer hover:text-main-green hover:w-10 hover:h-10">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <svg id="next_page" onClick={() => nextPage()} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-8 h-8 duration-200 cursor-pointer hover:text-main-green hover:w-10 hover:h-10">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
                {/* pagination here */}
            </div>
        </div>
    );
}
