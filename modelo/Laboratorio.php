<?php
    include_once 'Conexion.php';

    class Laboratorio {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($nombre, $avatar){
            $sql = "SELECT id_laboratorio, estado FROM laboratorio where nombre=:nombre";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                foreach ($this->objetos as $lab) {
                    $id_laboratorio = $lab->id_laboratorio;
                    $lab_estado = $lab->estado;
                }
                if ($lab_estado == 'a') {
                    echo 'noadd';
                }else{
                    $sql = "UPDATE laboratorio set estado='a' where id_laboratorio=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' =>$id_laboratorio));
                    echo 'add';
                }
            }else{
                $sql = "INSERT INTO laboratorio(nombre, avatar) 
                    values(:nombre, :avatar)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre, ':avatar' => $avatar));
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "SELECT * FROM laboratorio where estado='a' and nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "SELECT * FROM laboratorio where estado='a' and nombre not like '' order by id_laboratorio limit 25";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function cambiar_logo($id, $nombre){
            $sql = 'SELECT avatar FROM laboratorio where id_laboratorio=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchall();
            
            $sql = 'UPDATE laboratorio set avatar=:nombre where id_laboratorio=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre));
            return $this->objetos;
            
        }

        public function borrar($id){
            $sql = 'SELECT * FROM producto where prod_lab=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $prod = $query->fetchall();
            if (!empty($prod)) {
                echo 'noborrado';
            }else{
                $sql = "UPDATE laboratorio set estado='e' where id_laboratorio=:id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id));
                if (!empty($query->execute(array(':id' => $id)))) {
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            }
        }

        public function editar($nombre, $id_editado){
            $sql = 'UPDATE laboratorio set nombre=:nombre where id_laboratorio=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_editado, ':nombre' => $nombre));
            echo 'edit';
        }

        public function rellenar_laboratorios(){
            $sql = 'SELECT * FROM laboratorio order by nombre asc';
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }
    }