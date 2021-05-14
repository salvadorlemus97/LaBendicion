<?php
    include_once 'Conexion.php';

    class Presentacion {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($nombre){
            $sql = "SELECT id_presentacion, estado FROM presentacion where nombre=:nombre";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                foreach ($this->objetos as $pre) {
                    $pre_id = $pre->id_presentacion;
                    $pre_estado = $pre->estado;
                }
                if ($pre_estado == 'a') {
                    echo 'noadd';
                }else{
                    $sql = "UPDATE presentacion set estado='a' where id_presentacion=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' =>$pre_id));
                    echo 'add';
                }
            }else{
                $sql = "INSERT INTO presentacion(nombre) values(:nombre)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre));
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "SELECT * FROM presentacion where estado='a' and nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "SELECT * FROM presentacion where estado='a' and nombre not like '' order by id_presentacion limit 25";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function borrar($id){
            $sql = 'SELECT * FROM producto where prod_present=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $present = $query->fetchall();
            if (!empty($present)) {
                echo 'noborrado';
            }else{
                $sql = "UPDATE presentacion set estado='e' where id_presentacion=:id";
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
            $sql = 'UPDATE presentacion set nombre=:nombre where id_presentacion=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_editado, ':nombre' => $nombre));
            echo 'edit';
        }

        public function rellenar_presentaciones(){
            $sql = 'SELECT * FROM presentacion order by nombre asc';
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }
    }