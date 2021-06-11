<?php
    include_once 'Conexion.php';

    class Cliente {
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('nombre' => $consulta, 'estado' => 'a');
                $coleccion = $baseDeDatos->cliente;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('estado' => 'a');
                $coleccion = $baseDeDatos->cliente;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function crear($nombre, $apellido, $dui, $nacimiento, $telefono, $correo, $sexo, $adicional, $avatar){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('nombre' => $nombre, 'apellidos' => $apellido, 'dui' => $dui);
            $coleccion = $baseDeDatos->cliente;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (!empty($coleccion->count($param))) {
                foreach ($this->objetos as $clie) {
                    $clie_id = $clie->id_cliente;
                    $clie_estado = $clie->estado;
                }
                if ($clie_estado == 'a') {
                    echo 'noadd';
                }else{
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->cliente;
                    $resultado = $coleccion->updateOne(
                        ["id_cliente" => $clie_id],
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
                $coleccion = $baseDeDatos->cliente;
                $count =  $coleccion->count();
                $resultado = $coleccion->insertOne([
                    "id_cliente" => ($count+1),
                    "nombre" => $nombre,
                    "apellidos" => $apellido,
                    "edad" => $nacimiento,
                    "dui" => $dui,
                    "avatar" => $avatar,
                    "telefono" => $telefono,
                    "correo" => $correo,
                    "sexo" => $sexo,
                    "adicional" => $adicional,
                    "estado" => 'a',
                ]);
                echo 'add';
            }
        }

        public function editar($telefono, $correo, int $id, $adicional){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_cliente' => $id);
            $coleccion = $baseDeDatos->cliente;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            if (empty($this->objetos)) {
                echo 'noedit';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->cliente;
                $resultado = $coleccion->updateOne(
                    ["id_cliente" => $id],
                    [
                        '$set' => [
                            "telefono" => $telefono,
                            "correo" => $correo,
                            "adicional" => $adicional,
                        ],
                    ]
                );
                echo 'edit';
            }  
        }

        public function borrar(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->cliente;
            $resultado = $coleccion->updateOne(
                ["id_cliente" => $id],
                [
                    '$set' => [
                        "estado" => 'e',
                    ],
                ]
            );
            if (!empty($resultado)) {
                echo 'borrado';
            }else{
                echo 'noborrado';
            }
        }

        public function rellenar_clientes(){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('estado' => 'a');
            $coleccion = $baseDeDatos->cliente;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
            return $this->objetos;
        }

        public function buscar_datos_cliente(int $id_cliente){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_cliente' => $id_cliente);
            $coleccion = $baseDeDatos->cliente;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
            return $this->objetos;
        }
    }