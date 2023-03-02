<?php

namespace Server\Controllers;

class HomeController
{
    public function view(array $params = [])
    {
        $username = $params['username'] ?? 'Guest';
        include 'Client/pages/Home/Home.php';
    }

    public function insert(array $params) 
    {
        $item = [
            'name' => $params['name'],
            'message' => $params['message'],
        ];
        var_dump($item);
    }

    public function logout(){
        session_destroy();
        header("Location: /login");
        
    }
}
