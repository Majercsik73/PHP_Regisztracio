<?php
    class DB{
        private $server = "localhost";
        private $uName = "root";
        private $password = "";
        private $dbName = "burgeretterem";
        private $connection;

        private function connect(){
            $this->connection = mysqli_connect(
                $this->server,
                $this->uName,
                $this->password,
                $this->dbName
            );
            if ($this->connection){
                return "A kapcsolat létrejött";
            }
            else{
                return "A kapcsolat nem jött létre";
            }
        }

        private function close(){
            mysqli_close($this->connection);
        }

        public function query($sqlcmd){
            $this->connect();
            $result = mysqli_query($this->connection, $sqlcmd);
            $this->close();
            return $result;
        }
        
    }
    
    $db = new DB();
?>