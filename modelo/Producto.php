<?php
    include_once 'Conexion.php';

    class Producto {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function crear($nombre, $avatar, $concentracion, $adicional, $precio, int $laboratorio, int $tipo, int $presentacion){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('nombre' => $nombre, 'concentracion' => $concentracion, 'adicional' => $adicional, 'prod_lab' => $laboratorio, 'prod_tip_prod' => $tipo, 'prod_present' => $presentacion);
            $coleccion = $baseDeDatos->producto;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (!empty($coleccion->count($param))) {
                foreach ($this->objetos as $prod) {
                    $prod_id_producto = $prod->id_producto;
                    $prod_estado = $prod->estado;
                }
                if ($prod_estado == 'a') {
                    echo 'noadd';
                }else{
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->producto;
                    $resultado = $coleccion->updateOne(
                        ["id_producto" => $prod_id_producto],
                        [
                            '$set' => [
                                "estado" => 'a',
                            ],
                        ]
                    );
                    echo 'add';
                }
                
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->producto;
                $count =  $coleccion->count();
                $resultado = $coleccion->insertOne([
                    "id_producto" => ($count+1),
                    "nombre" => $nombre,
                    "concentracion" => $concentracion,
                    "adicional" => $adicional,
                    "precio" => $precio,
                    "avatar" => $avatar,
                    "estado" => 'a',
                    "prod_lab" => $laboratorio,
                    "prod_tip_prod" => $tipo,
                    "prod_present" => $presentacion,
                ]);
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('nombre' => $consulta, 'estado' => 'a');
                $coleccion = $baseDeDatos->producto;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('estado' => 'a');
                $coleccion = $baseDeDatos->producto;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function cambiar_logo(int $id, $nombre){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->producto;
            $resultado = $coleccion->updateOne(
                ["id_producto" => $id],
                [
                    '$set' => [
                        "avatar" => $nombre,
                    ],
                ]
            );
        }

        public function editar(int $id, $nombre, $concentracion, $adicional, $precio, int $laboratorio, int $tipo, int $presentacion){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_producto' => '$ne:'.$id, 'nombre' => $nombre, 'concentracion' => $concentracion, 'adicional' => $adicional, 'prod_lab' => $laboratorio, 'prod_tip_prod' => $tipo, 'prod_present' => $presentacion);
            $coleccion = $baseDeDatos->producto;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (!empty($coleccion->count($param))) {
                echo 'noedit';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->producto;
                $resultado = $coleccion->updateOne(
                    ["id_producto" => $id],
                    [
                        '$set' => [
                            "nombre" => $nombre,
                            "concentracion" => $concentracion,
                            "adicional" => $adicional,
                            "precio" => $precio,
                            "estado" => 'a',
                            "prod_lab" => $laboratorio,
                            "prod_tip_prod" => $tipo,
                            "prod_present" => $presentacion,
                        ],
                    ]
                );
                echo 'edit';
            }
        }

        public function borrar(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('lote_id_prod' => $id);
            $coleccion = $baseDeDatos->lote;
            $present = $coleccion->find($param); // criterio de búsqueda
            if (!empty($coleccion->count($param))) {
                echo 'noborrado';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->producto;
                $resultado = $coleccion->updateOne(
                    ["id_producto" => $id],
                    [
                        '$set' => [
                            "estado" => 'e',
                        ],
                    ]
                );
                echo 'borrado';
            }
        }

        public function obtener_stock(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('lote_id_prod' => $id);
            $coleccion = $baseDeDatos->lote;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
            return $this->objetos;
            
        }

        public function buscar_id(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_producto' => $id);
            $coleccion = $baseDeDatos->producto;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
            return $this->objetos;
        }

        public function reporte_producto(){         
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->producto;
            $this->objetos = $coleccion->find(); // criterio de búsqueda
            return $this->objetos;            
        }

        public function obtener_prod_id(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_producto' => $id);
            $coleccion = $baseDeDatos->producto;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda
            return  $this->objetos;
        }
    }