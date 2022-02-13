<?php
    class Database {
        private $host = "localhost"; //domainที่รัน database
        private $database_name = "resume"; //ชื่อตาราง
        private $username = "root";
        private $password = "";
        private $port = "3306"; //พอตที่รันของ database

        public $conn;

        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO(
                    "mysql:host=" .$this->host . ";
                    port=" . $this->port. ";
                    dbname=" .$this->database_name,
                    $this->username,
                    $this->password
                );
            } catch (PDOException $exception) {
                echo "Database could not be connection:" .
                $exception->getmessage();
            }
            return $this->conn;
        }
    }
?>