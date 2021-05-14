<?php
    include_once 'Conexion.php';

    class VentaProducto {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function ver($id){
            $sql = "SELECT vp.precio, vp.cantidad, p.nombre AS producto, p.concentracion, p.adicional, l.nombre AS laboratorio, 
                    pre.nombre AS presentacion, tp.nombre AS tipo, vp.subtotal
                    from venta_producto vp 
                    join producto p on vp.producto_id_producto=p.id_producto 
                    join laboratorio l ON p.prod_lab=l.id_laboratorio 
                    JOIN tipo_producto tp ON p.prod_tip_prod=tp.id_tip_prod 
                    JOIN presentacion pre ON p.prod_present=pre.id_presentacion 
                    WHERE vp.venta_id_venta=:id ";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function borrar($id_venta){
            $sql = "DELETE FROM venta_producto where venta_id_venta=:id_venta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_venta' => $id_venta));
        }

        
    }