<?php
    class Message{

        // Connection
        private $conn;

        // Table
        private $db_table = "mensagens";

        // Columns
        public $id;
        public $chave;
        public $path;
        public $mensagem;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getMessages(){
            $sqlQuery = "SELECT id, chave, path, mensagem FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // CREATE
        public function createMessage(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        chave = :chave, 
                        path = :path, 
                        mensagem = :mensagem";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->chave=htmlspecialchars(strip_tags($this->chave));
            $this->path=htmlspecialchars(strip_tags($this->path));
            $this->mensagem=htmlspecialchars(strip_tags($this->mensagem));
        
            // bind data
            $stmt->bindParam(":chave", $this->chave);
            $stmt->bindParam(":path", $this->path);
            $stmt->bindParam(":mensagem", $this->mensagem);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // UPDATE
        public function getSingleMessage(){
            $sqlQuery = "SELECT
                        id, 
                        chave, 
                        path, 
                        mensagem
                      FROM
                        ". $this->db_table ."
                    WHERE 
                       id = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->chave = $dataRow['chave'];
            $this->path = $dataRow['path'];
            $this->mensagem = $dataRow['mensagem'];
        }        

        // UPDATE
        public function updateMessage(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        chave = :chave, 
                        path = :path, 
                        mensagem = :mensagem
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->chave=htmlspecialchars(strip_tags($this->chave));
            $this->path=htmlspecialchars(strip_tags($this->path));
            $this->mensagem=htmlspecialchars(strip_tags($this->mensagem));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":chave", $this->chave);
            $stmt->bindParam(":path", $this->path);
            $stmt->bindParam(":mensagem", $this->mensagem);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // DELETE
        function deleteMessage(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }

    }
?>

