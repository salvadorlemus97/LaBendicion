<?php
    include_once 'Conexion.php';

    class Lote {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function crear(int $id_producto, int $proveedor, $stock, $vencimiento){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->lote;
            $count =  $coleccion->count();
            $resultado = $coleccion->insertOne([
                "id_lote" => ($count+1),
                "stock" => $stock,
                "vencimiento" => $vencimiento,
                "lote_id_prod" => $id_producto,
                "lote_id_prov" => $proveedor,
            ]);
            echo 'add';
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('nombre' => $consulta);
                $coleccion = $baseDeDatos->producto;
                $nompro = $coleccion->findOne($param); // criterio de búsqueda

                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('lote_id_prod' => $nompro->id_producto);
                $coleccion = $baseDeDatos->lote;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->lote;
                $this->objetos = $coleccion->find(); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function editar(int $id, $stock){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->lote;
            $resultado = $coleccion->updateOne(
                ["id_lote" => $id],
                [
                    '$set' => [
                        "stock" => $stock,
                    ],
                ]
            );
            echo 'edit';
        }

        public function borrar(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->lote;
            $resultado = $coleccion->deleteOne(
                ["id_lote" => $id]
            );
            if (!empty($resultado)) {
                echo 'borrado';
            }else{
                echo 'noborrado';
            }
        }
    }