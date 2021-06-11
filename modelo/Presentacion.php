<?php
    include_once 'Conexion.php';

    class Presentacion {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function crear($nombre){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('nombre' => $nombre);
            $coleccion = $baseDeDatos->presentacion;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (!empty($coleccion->count($param))) {
                foreach ($this->objetos as $pre) {
                    $pre_id = $pre->id_presentacion;
                    $pre_estado = $pre->estado;
                }
                if ($pre_estado == 'a') {
                    echo 'noadd';
                }else{
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->presentacion;
                    $resultado = $coleccion->updateOne(
                        ["id_presentacion" => $pre_id],
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
                $coleccion = $baseDeDatos->presentacion;
                $count =  $coleccion->count();
                $resultado = $coleccion->insertOne([
                    "id_presentacion" => ($count+1),
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
                $coleccion = $baseDeDatos->presentacion;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('estado' => 'a');
                $coleccion = $baseDeDatos->presentacion;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function borrar(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('prod_present' => $id);
            $coleccion = $baseDeDatos->producto;
            $present = $coleccion->find($param); // criterio de búsqueda
            if (!empty($coleccion->count($param))) {
                echo 'noborrado';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->presentacion;
                $resultado = $coleccion->updateOne(
                    ["id_presentacion" => $id],
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
            $coleccion = $baseDeDatos->presentacion;
            $resultado = $coleccion->updateOne(
                ["id_presentacion" => $id_editado],
                [
                    '$set' => [
                        "nombre" => $nombre,
                    ],
                ]
            );
            echo 'edit';
        }

        public function rellenar_presentaciones(){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->presentacion;
            $this->objetos = $coleccion->find(); // criterio de búsqueda
            return $this->objetos;
        }

        public function obtener_present_id(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_presentacion' => $id);
            $coleccion = $baseDeDatos->presentacion;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda
            return  $this->objetos;
        }
    }