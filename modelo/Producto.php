<?php
    include_once 'Conexion.php';

    class Producto {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($nombre, $avatar, $concentracion, $adicional, $precio, $laboratorio, $tipo, $presentacion){
            $sql = "SELECT id_producto, estado FROM producto where nombre=:nombre and concentracion=:concentracion and adicional=:adicional and prod_lab=:laboratorio and prod_tip_prod=:tipo and prod_present=:presentacion";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre, ':concentracion' => $concentracion, ':adicional' => $adicional, ':laboratorio' => $laboratorio, ':tipo' => $tipo, ':presentacion' => $presentacion));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                foreach ($this->objetos as $prod) {
                    $prod_id_producto = $prod->id_producto;
                    $prod_estado = $prod->estado;
                }
                if ($prod_estado == 'a') {
                    echo 'noadd';
                }else{
                    $sql = "UPDATE producto set estado='a' where id_producto=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' =>$prod_id_producto));
                    echo 'add';
                }
                
            }else{
                $sql = "INSERT INTO producto(nombre, avatar, concentracion, adicional, precio, prod_lab, prod_tip_prod, prod_present) 
                    values(:nombre, :avatar, :concentracion, :adicional, :precio, :laboratorio, :tipo, :presentacion)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre, ':avatar' => $avatar, ':concentracion' => $concentracion, ':adicional' => $adicional, ':precio' => $precio, ':laboratorio' => $laboratorio, ':tipo' => $tipo, ':presentacion' => $presentacion));
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "
                    SELECT p.id_producto, p.nombre AS nombre, p.concentracion, p.adicional, p.precio, l.nombre AS laboratorio, 
                    tp.nombre as tipo, pre.nombre AS presentacion, p.avatar AS avatar, p.prod_lab, p.prod_tip_prod, p.prod_present
                    FROM producto p
                    JOIN laboratorio l ON l.id_laboratorio=p.prod_lab
                    JOIN tipo_producto tp ON tp.id_tip_prod=p.prod_tip_prod
                    JOIN presentacion pre ON pre.id_presentacion=p.prod_present
                    WHERE p.estado = 'a' and p.nombre LIKE :consulta 
                    order by p.nombre LIMIT 25;
                ";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "
                    SELECT p.id_producto, p.nombre AS nombre, p.concentracion, p.adicional, p.precio, l.nombre AS laboratorio, 
                    tp.nombre as tipo, pre.nombre AS presentacion, p.avatar AS avatar, p.prod_lab, p.prod_tip_prod, p.prod_present
                    FROM producto p
                    JOIN laboratorio l ON l.id_laboratorio=p.prod_lab
                    JOIN tipo_producto tp ON tp.id_tip_prod=p.prod_tip_prod
                    JOIN presentacion pre ON pre.id_presentacion=p.prod_present
                    WHERE p.estado = 'a' and p.nombre NOT LIKE '' 
                    order by p.nombre LIMIT 25;
                ";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function cambiar_logo($id, $nombre){
            $sql = "UPDATE producto set avatar=:nombre where id_producto=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre));
        }

        public function editar($id, $nombre, $concentracion, $adicional, $precio, $laboratorio, $tipo, $presentacion){
            $sql = "SELECT id_producto FROM producto where id_producto !=:id and nombre=:nombre and concentracion=:concentracion and adicional=:adicional and prod_lab=:laboratorio and prod_tip_prod=:tipo and prod_present=:presentacion";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre, ':concentracion' => $concentracion, ':adicional' => $adicional, ':laboratorio' => $laboratorio, ':tipo' => $tipo, ':presentacion' => $presentacion));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                echo 'noedit';
            }else{
                $sql = "UPDATE producto set nombre=:nombre, concentracion=:concentracion, adicional=:adicional, prod_lab=:laboratorio, prod_tip_prod=:tipo, prod_present=:presentacion, precio=:precio where id_producto=:id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id, ':nombre' => $nombre, ':concentracion' => $concentracion, ':adicional' => $adicional, ':precio' => $precio, ':laboratorio' => $laboratorio, ':tipo' => $tipo, ':presentacion' => $presentacion));
                echo 'edit';
            }
        }

        public function borrar($id){
            $sql = "SELECT * from lote where lote_id_prod=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $lote = $query->fetchall();
            if (!empty($lote)) {
                echo 'noborrado';
            }else{
                $sql = "UPDATE producto set estado='e' where id_producto=:id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id));
                if (!empty($query->execute(array(':id' => $id)))) {
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            }
        }

        public function obtener_stock($id){
            $sql = "SELECT SUM(stock) as total from lote where lote_id_prod=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchall();
            return $this->objetos;
            
        }

        public function buscar_id($id){
                $sql = "
                    SELECT p.id_producto, p.nombre AS nombre, p.concentracion, p.adicional, p.precio, l.nombre AS laboratorio, 
                    tp.nombre as tipo, pre.nombre AS presentacion, p.avatar AS avatar, p.prod_lab, p.prod_tip_prod, p.prod_present
                    FROM producto p
                    JOIN laboratorio l ON l.id_laboratorio=p.prod_lab
                    JOIN tipo_producto tp ON tp.id_tip_prod=p.prod_tip_prod
                    JOIN presentacion pre ON pre.id_presentacion=p.prod_present
                    WHERE p.id_producto=:id
                    order by p.nombre;
                ";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id));
                $this->objetos = $query->fetchall();
                return $this->objetos;
        }

        public function reporte_producto(){            
            $sql = "SELECT p.id_producto, p.nombre AS nombre, p.concentracion, p.adicional, p.precio, l.nombre AS laboratorio, 
                tp.nombre as tipo, pre.nombre AS presentacion, p.avatar AS avatar, p.prod_lab, p.prod_tip_prod, p.prod_present
                FROM producto p
                JOIN laboratorio l ON l.id_laboratorio=p.prod_lab
                JOIN tipo_producto tp ON tp.id_tip_prod=p.prod_tip_prod
                JOIN presentacion pre ON pre.id_presentacion=p.prod_present
                WHERE p.nombre NOT LIKE '' 
                order by p.nombre;
            ";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;            
        }
    }