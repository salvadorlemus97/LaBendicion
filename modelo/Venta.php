<?php
    include_once 'Conexion.php';

    class Venta {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($id_cliente, $total, $fecha, $vendedor){
            $sql = "INSERT INTO venta(fecha, total, vendedor, id_cliente) 
                values(:fecha, :total, :vendedor, :cliente)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':fecha' => $fecha, ':total' => $total, ':vendedor' => $vendedor, ':cliente' => $id_cliente));
            echo 'add';
        }

        public function ultima_venta(){
            $sql = 'SELECT MAX(id_venta)as ultima_venta FROM venta';
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function borrar($id_venta){
            $sql = "DELETE from venta where id_venta=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_venta));
        }

        public function buscar(){
            $sql = "SELECT v.id_venta, v.fecha, v.cliente, v.dui, v.total, concat(u.nombre_us,' ',u.apellidos_us )as vendedor, id_cliente  
                    FROM venta v
                    join usuario u on u.id_usuario=v.vendedor";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        // aun no se esta utilizando xq son premiun
        public function verificar($id_venta, $id_usuario){
            $sql = "SELECT * from venta where vendedor=:id_usuario and id_venta=:id_venta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario, ':id_venta' => $id_venta));
            $this->objetos = $query->fetchall();
            if (!empty($this->objetos)) {
                return 1;
            }else{
                return 0;
            }            
        }

        // aun no se esta ocupando
        public function recuperar_vendedor($id_venta){
            $sql = "SELECT us_tipo from venta join usuario on id_usuario=vendedor where id_venta=:id_venta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_venta' => $id_venta));
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function vente_dia_vendedor($id_usuario){
            $sql = "SELECT SUM(total) as vente_dia_vendedor from venta where vendedor=:id_usuario and date(fecha)=date(curdate())";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario));
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function venta_diaria(){
            $sql = "SELECT SUM(total)as venta_diaria from venta where date(fecha)=date(curdate())";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function venta_mensual(){
            $sql = "SELECT SUM(total)as venta_mensual from venta where year(fecha)=year(CURDATE()) AND MONTH(fecha)=MONTH(CURDATE())";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function venta_anual(){
            $sql = "SELECT SUM(total)as venta_anual from venta where year(fecha)=year(CURDATE())";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }
    }