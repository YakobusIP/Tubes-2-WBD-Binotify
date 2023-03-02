<?php
    namespace Server\Controllers;

    use PDO;

    // Database controller class
    class Controller {
        protected $database;

        public function __construct()
        {
            $dsn = "pgsql:host=bnmo-php-db;port=5433;dbname=bnmo;";
            $user = "postgres";
            $password = "postgres";
            try {
                $this->database = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            } catch (\PDOException $e) {
                die($e->getMessage());
            }
        }
    }
?>