<?php
    class Conexion{
        private $servidor = 'localhost';
        private $db = 'farmacia_chamba';
        private $puerto = 3306;
        private $charset = 'utf8';
        private $usuario = 'root';
        private $contrasena = '';
        public $pdo = null;
        private $atributos = 
            [
                PDO::ATTR_CASE=>PDO::CASE_LOWER, 
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_ORACLE_NULLS=>PDO::NULL_EMPTY_STRING, 
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ
            ];

        public function __construct(){
            //configurar la conexion
            $dsn = "mysql:dbname={$this->db};host={$this->servidor};port={$this->puerto};charset={$this->charset}";

            //crear una instancia de PDO
            try {
                $this->pdo = new PDO($dsn, $this->usuario, $this->contrasena, $this->atributos);
                //configurar para caracteres especiales
                $this->pdo->exec('set names utf8');
                $this->pdo->exec('set lc_time_names = "es_SV"');
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        public function __destruct(){
            $this->pdo = null;
        }  
    }