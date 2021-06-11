<?php
    include_once 'Conexion.php';

    class Laboratorio {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function crear($nombre, $avatar){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('nombre' => $nombre);
            $coleccion = $baseDeDatos->laboratorio;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (!empty($coleccion->count($param))) {
                foreach ($this->objetos as $lab) {
                    $id_laboratorio = $lab->id_laboratorio;
                    $lab_estado = $lab->estado;
                }
                if ($lab_estado == 'a') {
                    echo 'noadd';
                }else{
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->laboratorio;
                    $resultado = $coleccion->updateOne(
                        ["id_laboratorio" => $id_laboratorio],
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
                $coleccion = $baseDeDatos->laboratorio;
                $count =  $coleccion->count();
                $resultado = $coleccion->insertOne([
                    "id_laboratorio" => ($count+1),
                    "nombre" => $nombre,
                    "avatar" => $avatar,
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
                $coleccion = $baseDeDatos->laboratorio;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('estado' => 'a');
                $coleccion = $baseDeDatos->laboratorio;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function cambiar_logo(int $id, $nombre){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_laboratorio' => $id);
            $coleccion = $baseDeDatos->laboratorio;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->laboratorio;
            $resultado = $coleccion->updateOne(
                ["id_laboratorio" => $id],
                [
                    '$set' => [
                        "avatar" => $nombre,
                    ],
                ]
            );
            return $this->objetos;
            
        }

        public function borrar(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('prod_lab' => $id);
            $coleccion = $baseDeDatos->producto;
            $prod = $coleccion->find($param); // criterio de búsqueda
            if (!empty($coleccion->count($param))) {
                echo 'noborrado';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->laboratorio;
                $resultado = $coleccion->updateOne(
                    ["id_laboratorio" => $id],
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
            $coleccion = $baseDeDatos->laboratorio;
            $resultado = $coleccion->updateOne(
                ["id_laboratorio" => $id_editado],
                [
                    '$set' => [
                        "nombre" => $nombre,
                    ],
                ]
            );
            echo 'edit';
        }

        public function rellenar_laboratorios(){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->laboratorio;
            $this->objetos = $coleccion->find(); // criterio de búsqueda
            return $this->objetos;
        }

        public function obtener_lab_id(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_laboratorio' => $id);
            $coleccion = $baseDeDatos->laboratorio;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda
            return  $this->objetos;
        }
    }