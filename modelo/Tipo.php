<?php
    include_once 'Conexion.php';

    class Tipo {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($nombre){
            $sql = "SELECT id_tip_prod, estado FROM tipo_producto where nombre=:nombre";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                foreach ($this->objetos as $tipo) {
                    $tipo_id = $tipo->id_tip_prod;
                    $tipo_estado = $tipo->estado;
                }
                if ($tipo_estado == 'a') {
                    echo 'noadd';
                }else{
                    $sql = "UPDATE tipo_producto set estado='a' where id_tip_prod=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' =>$tipo_id));
                    echo 'add';
                }
            }else{
                $sql = "INSERT INTO tipo_producto(nombre) values(:nombre)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre));
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "SELECT * FROM tipo_producto where estado='a' and nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "SELECT * FROM tipo_producto where estado='a' and nombre not like '' order by id_tip_prod limit 25";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function borrar($id){
            $sql = 'SELECT * FROM producto where prod_tip_prod=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $tipo = $query->fetchall();
            if (!empty($tipo)) {
                echo 'noborrado';
            }else{
                $sql = "UPDATE tipo_producto set estado='e' where id_tip_prod=:id";
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
            $sql = 'UPDATE tipo_producto set nombre=:nombre where id_tip_prod=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_editado, ':nombre' => $nombre));
            echo 'edit';
        }

        public function rellenar_tipos(){
            $sql = 'SELECT * FROM tipo_producto order by nombre asc';
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }
    }