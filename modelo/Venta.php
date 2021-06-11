<?php
    include_once 'Conexion.php';

    class Venta {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function crear(int $id_cliente, $total, $fecha, int $vendedor){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $count =  $coleccion->count();
            $resultado = $coleccion->insertOne([
                "id_venta" => ($count+1),
                "fecha" => $fecha,
                "total" => $total,
                "vendedor" => $vendedor,
                "id_cliente" => $id_cliente,
                "cliente" => '',
                "dui" => '',
            ]);
            return $count;
            // echo 'add';
        }

        public function ultima_venta(){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->find(); // criterio de búsqueda

            // $sql = 'SELECT MAX(id_venta)as ultima_venta FROM venta';
            // $query = $this->acceso->prepare($sql);
            // $query->execute();
            // $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function borrar(int $id_venta){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $resultado = $coleccion->deleteOne(
                ["id_venta" => $id_venta]
            );
        }

        public function buscar(){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->find(); // criterio de búsqueda
            return $this->objetos;
        }

        // aun no se esta utilizando xq son premiun
        public function verificar(int $id_venta, int $id_usuario){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('vendedor' => $id_usuario, 'id_venta' => $id_venta);
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda

            // $sql = "SELECT * from venta where vendedor=:id_usuario and id_venta=:id_venta";
            // $query = $this->acceso->prepare($sql);
            // $query->execute(array(':id_usuario' => $id_usuario, ':id_venta' => $id_venta));
            // $this->objetos = $query->fetchall();
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
            $fecha = date('Y-m-d H:i:s');
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->find(array('vendedor' => $id_usuario)); // criterio de búsqueda
            
            return $this->objetos;
        }

        public function venta_diaria(){
            $fecha = date('Y-m-d H:i:s');
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->find(); // criterio de búsqueda

            return $this->objetos;
        }

        public function venta_mensual(){
            $fecha = date('Y-m-d H:i:s');
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->find(); // criterio de búsqueda

            return $this->objetos;
        }

        public function venta_anual(){
            $fecha = date('Y-m-d H:i:s');
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->venta;
            $this->objetos = $coleccion->find(); // criterio de búsqueda

            return $this->objetos;
        }
    }