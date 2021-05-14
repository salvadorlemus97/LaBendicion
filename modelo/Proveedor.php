<?php
    include_once 'Conexion.php';

    class Proveedor {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($nombre, $telefono, $correo, $direccion, $avatar){
            $sql = "SELECT id_proveedor, estado FROM proveedor where nombre=:nombre and telefono=:telefono and correo=:correo and direccion=:direccion";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre, ':telefono' => $telefono, ':correo' => $correo, ':direccion' => $direccion));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                foreach ($this->objetos as $prov) {
                    $prov_id = $prov->id_proveedor;
                    $prov_estado = $prov->estado;
                }
                if ($prov_estado == 'a') {
                    echo 'noadd';
                }else{
                    $sql = "UPDATE proveedor set estado='a' where id_proveedor=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' =>$prov_id));
                    echo 'add';
                }
            }else{
                $sql = "INSERT INTO proveedor(nombre, avatar, telefono, correo, direccion) 
                    values(:nombre, :avatar, :telefono, :correo, :direccion)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre, ':avatar' => $avatar, ':telefono' => $telefono, ':correo' => $correo, ':direccion' => $direccion));
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "SELECT * FROM proveedor where estado != 'e' and nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "SELECT * FROM proveedor where estado != 'e' and nombre not like '' order by id_proveedor desc limit 25";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function cambiar_logo($id, $nombre){
            $sql = "UPDATE proveedor set avatar=:nombre where id_proveedor=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre));
        }

        public function borrar($id){
            $sql = 'SELECT * FROM lote where lote_id_prov=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $lote = $query->fetchall();
            if (!empty($lote)) {
                echo 'noborrado';
            }else{
                $sql = "UPDATE proveedor set estado='e' where id_proveedor=:id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id));
                if (!empty($query->execute(array(':id' => $id)))) {
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            }
        }

        public function editar($id, $nombre, $telefono, $correo, $direccion){
            $sql = "SELECT id_proveedor FROM proveedor where id_proveedor !=:id and nombre=:nombre";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                echo 'noedit';
            }else{
                $sql = "UPDATE proveedor set nombre=:nombre, telefono=:telefono, correo=:correo, direccion=:direccion where id_proveedor=:id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id, ':nombre' => $nombre, ':telefono' => $telefono, ':correo' => $correo, ':direccion' => $direccion));
                echo 'edit';
            }  
        }

        public function rellenar_proveedores(){
            $sql = "SELECT * FROM proveedor order by nombre asc";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }
    }