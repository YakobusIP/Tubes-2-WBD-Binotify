import { useNavigate } from 'react-router-dom';
import { useEffect } from "react";

export default function LoginRedirect() {
    const navigate = useNavigate();
    useEffect(() => {
        navigate('/login');
        document.title = 'Binotify Premium';
    }, []);

    return (
        <></>
    );
}
