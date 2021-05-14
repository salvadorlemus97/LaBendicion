<?php
    include_once 'Conexion.php';

    class Cliente {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "SELECT * FROM cliente where estado != 'e' and nombre LIKE :consulta";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "SELECT * FROM cliente where estado != 'e' and nombre not like '' order by id_cliente desc limit 25";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function crear($nombre, $apellido, $dui, $nacimiento, $telefono, $correo, $sexo, $adicional, $avatar){
            $sql = "SELECT id_cliente, estado FROM cliente where nombre=:nombre and apellidos=:apellido and dui=:dui";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':dui' => $dui));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                foreach ($this->objetos as $clie) {
                    $clie_id = $clie->id_cliente;
                    $clie_estado = $clie->estado;
                }
                if ($clie_estado == 'a') {
                    echo 'noadd';
                }else{
                    $sql = "UPDATE cliente set estado='a' where id_cliente=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' =>$clie_id));
                    echo 'add';
                }
            }else{
                $sql = "INSERT INTO cliente(nombre, apellidos, dui, edad, telefono, correo, sexo, adicional, avatar) 
                    values(:nombre, :apellido, :dui, :edad, :telefono, :correo, :sexo, :adicional, :avatar)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':dui' => $dui, ':edad' => $nacimiento, ':telefono' => $telefono, ':correo' => $correo, ':sexo' => $sexo, ':adicional' => $adicional, ':avatar' => $avatar));
                echo 'add';
            }
        }

        public function editar($telefono, $correo, $id, $adicional){
            $sql = "SELECT id_cliente FROM cliente where id_cliente=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchall();

            if (empty($this->objetos)) {
                echo 'noedit';
            }else{
                $sql = "UPDATE cliente set telefono=:telefono, correo=:correo, adicional=:adicional where id_cliente=:id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id, ':telefono' => $telefono, ':correo' => $correo, ':adicional' => $adicional));
                echo 'edit';
            }  
        }

        public function borrar($id){
            $sql = "UPDATE cliente set estado='e' where id_cliente=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            if (!empty($query->execute(array(':id' => $id)))) {
                echo 'borrado';
            }else{
                echo 'noborrado';
            }
        }

        public function rellenar_clientes(){
            $sql = "SELECT * from cliente where estado='a' order by nombre asc";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function buscar_datos_cliente($id_cliente){
            $sql = "SELECT * from cliente where id_cliente=:id_cliente";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_cliente' => $id_cliente));
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }
    }