<?php
    include_once 'Conexion.php';

    class Tipo {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function crear($nombre){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('nombre' => $nombre);
            $coleccion = $baseDeDatos->tipo_producto;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (!empty($coleccion->count($param))) {
                foreach ($this->objetos as $tipo) {
                    $tipo_id = $tipo->id_tip_prod;
                    $tipo_estado = $tipo->estado;
                }
                if ($tipo_estado == 'a') {
                    echo 'noadd';
                }else{
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->tipo_producto;
                    $resultado = $coleccion->updateOne(
                        ["id_tip_prod" => $tipo_id],
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
                $coleccion = $baseDeDatos->tipo_producto;
                $count =  $coleccion->count();
                $resultado = $coleccion->insertOne([
                    "id_tip_prod" => ($count+1),
                    "nombre" => $nombre,
                    "estado" => 'a',
                ]);
                echo 'add';
            }
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('nombre' => $consulta, 'estado' => 'a');
                $coleccion = $baseDeDatos->tipo_producto;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('estado' => 'a');
                $coleccion = $baseDeDatos->tipo_producto;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function borrar(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('prod_tip_prod' => $id);
            $coleccion = $baseDeDatos->producto;
            $tipo = $coleccion->find($param); // criterio de búsqueda
            if (!empty($coleccion->count($param))) {
                echo 'noborrado';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->tipo_producto;
                $resultado = $coleccion->updateOne(
                    ["id_tip_prod" => $id],
                    [
                        '$set' => [
                            "estado" => 'e',
                        ],
                    ]
                );
                echo 'borrado';
            }
        }

        public function editar($nombre, int $id_editado){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->tipo_producto;
            $resultado = $coleccion->updateOne(
                ["id_tip_prod" => $id_editado],
                [
                    '$set' => [
                        "nombre" => $nombre,
                    ],
                ]
            );
            echo 'edit';
        }

        public function rellenar_tipos(){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->tipo_producto;
            $this->objetos = $coleccion->find(); // criterio de búsqueda
            return $this->objetos;
        }

        public function obtener_tipoprod_id(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_tip_prod' => $id);
            $coleccion = $baseDeDatos->tipo_producto;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda
            return  $this->objetos;
        }
    }