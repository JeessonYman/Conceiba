<?php
// Define la ruta base
define('BASE_URL', 'https://conceibaviernes.rf.gd/');
Class Database{

	private $server = "mysql:host=localhost:3309;dbname=conceibadatabase";
	private $username = "root";
	private $password = "";
	private $options  = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
	);
	protected $conn;
	
	public function open(){
 		try{
 			$this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
 			// Asegurar la codificación UTF-8
 			$this->conn->exec("SET CHARACTER SET utf8mb4");
 			return $this->conn;
 		}
 		catch (PDOException $e){
            throw new Exception("Error en la conexión: " . $e->getMessage());
 		}
    }
    
	public function close(){
   		$this->conn = null;
 	}
}

$pdo = new Database();
?>