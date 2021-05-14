<?php
    include_once 'Conexion.php';

    class Lote {
        var $objetos;
        var $acceso;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        public function crear($id_producto, $proveedor, $stock, $vencimiento){
            $sql = "INSERT INTO lote(stock, vencimiento, lote_id_prod, lote_id_prov) 
                values(:stock, :vencimiento, :id_producto, :id_proveedor)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':stock' => $stock, ':vencimiento' => $vencimiento, ':id_producto' => $id_producto, ':id_proveedor' => $proveedor));
            echo 'add';
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = "
                    SELECT l.id_lote, l.stock, l.vencimiento, prod.concentracion, prod.adicional, prod.nombre AS prod_nom, 
                    lab.nombre AS lab_nom, tp.nombre AS tip_nom, pre.nombre AS pre_nom, pv.nombre AS proveedor, prod.avatar AS logo 
                    FROM lote l 
                    JOIN proveedor pv ON pv.id_proveedor=l.lote_id_prov 
                    JOIN producto prod ON prod.id_producto=l.lote_id_prod 
                    JOIN laboratorio lab ON lab.id_laboratorio=prod.prod_lab 
                    JOIN tipo_producto tp ON tp.id_tip_prod=prod.prod_tip_prod 
                    JOIN presentacion pre ON pre.id_presentacion=prod.prod_present 
                    WHERE prod.nombre LIKE :consulta ORDER BY prod.nombre ASC LIMIT 25;
                ";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "
                    SELECT l.id_lote, l.stock, l.vencimiento, prod.concentracion, prod.adicional, prod.nombre AS prod_nom, 
                    lab.nombre AS lab_nom, tp.nombre AS tip_nom, pre.nombre AS pre_nom, pv.nombre AS proveedor, prod.avatar AS logo 
                    FROM lote l 
                    JOIN proveedor pv ON pv.id_proveedor=l.lote_id_prov 
                    JOIN producto prod ON prod.id_producto=l.lote_id_prod 
                    JOIN laboratorio lab ON lab.id_laboratorio=prod.prod_lab 
                    JOIN tipo_producto tp ON tp.id_tip_prod=prod.prod_tip_prod 
                    JOIN presentacion pre ON pre.id_presentacion=prod.prod_present 
                    WHERE prod.nombre NOT LIKE '' ORDER BY prod.nombre ASC LIMIT 25;
                ";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function editar($id, $stock){
            $sql = "UPDATE lote set stock=:stock where id_lote=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, 'stock' => $stock));
            echo 'edit';
        }

        public function borrar($id){
            $sql = "DELETE from lote where id_lote=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            if (!empty($query->execute(array(':id' => $id)))) {
                echo 'borrado';
            }else{
                echo 'noborrado';
            }
        }
    }