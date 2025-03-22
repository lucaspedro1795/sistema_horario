<?php

class Connection extends PDO{
    private $server = "localhost";
    private $user = "root";
    private $pass = "";
    private $bd = "sys_bandeira";
    private static $instance = null;

    public function __construct(){
        $dsn = "mysql:host={$this->server};dbname={$this->bd};charset=utf8mb4";
        
        try{
            parent::__construct($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ]);
        }catch(PDOException $e){
            die("Erro na conexão: ". $e->getMessage());
        }
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
}
?>
