<?php
    include_once 'Conexion.php';

    class Usuario{
        var $objetos;
        var $acceso;
        public function __construct(){
            // $db = new Conexion();
            // $this->acceso = $db->pdo;
            // $this->acceso = obtenerBaseDeDatos();
        }

        public function Loguearse(int $dui, $pass){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('dui_us' => $dui);
            $coleccion = $baseDeDatos->usuario;
            $objetoss = $coleccion->find(array('dui_us' => $dui)); // criterio de búsqueda
            foreach ($objetoss as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    return "logueado";
                }
            }else{
                if ($pass == $contrasena_actual) {
                    return "logueado";
                }
            }            
        }

        public function obtener_dato_logueo(int $user){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('dui_us' => $user);
            $coleccion = $baseDeDatos->usuario;
            $objetoss = $coleccion->find($param); // criterio de búsqueda
            return $objetoss;
        }

        public function obtener_datos(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
            return  $this->objetos;
        }

        public function obtener_datos_id(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda
            return  $this->objetos;
        }

        public function obtener_tipo_us(int $id_tipo){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_tipo_us' => $id_tipo);
            $coleccion = $baseDeDatos->tipo_us;
            $this->objetos = $coleccion->findOne($param); // criterio de búsqueda
            return  $this->objetos;
        }

        public function editar($id_usuario, $telefono, $residencia, $correo, $sexo, $adicional){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->usuario;
            $resultado = $coleccion->updateOne(
                // El criterio, algo así como where
                ["id_usuario" => $id_usuario],
                // Nuevos valores, no es necesario poner todos pero aquí es para ejemplificar
                [
                    '$set' => [
                        "telefono_us" => $telefono,
                        "residencia_us" => $residencia,
                        "correo_us" => $correo,
                        "sexo_us" => $sexo,
                        "adicional_us" => $adicional,
                    ],
                ]
            );
        }

        public function cambiar_contra($id_usuario, $oldpass, $newpass){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id_usuario);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($oldpass, $contrasena_actual)) {
                    $pass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 10]);
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->updateOne(
                        // El criterio, algo así como where
                        ["id_usuario" => $id_usuario],
                        // Nuevos valores, no es necesario poner todos pero aquí es para ejemplificar
                        [
                            '$set' => [
                                "contrasena_us" => $pass,
                            ],
                        ]
                    );
                    echo 'update';
                }else{
                    echo 'noupdate';
                }
            }else{
                if ($oldpass == $contrasena_actual) {
                    $pass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 10]);
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->updateOne(
                        // El criterio, algo así como where
                        ["id_usuario" => $id_usuario],
                        // Nuevos valores, no es necesario poner todos pero aquí es para ejemplificar
                        [
                            '$set' => [
                                "contrasena_us" => $pass,
                            ],
                        ]
                    );
                    echo 'update';
                }else{
                    echo 'noupdate';
                }
            }
        }

        public function cambiar_foto($id_usuario, $nombre){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id_usuario);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->usuario;
            $resultado = $coleccion->updateOne(
                // El criterio, algo así como where
                ["id_usuario" => $id_usuario],
                // Nuevos valores, no es necesario poner todos pero aquí es para ejemplificar
                [
                    '$set' => [
                        "avatar" => $nombre,
                    ],
                ]
            );
            return $this->objetos;
            
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $baseDeDatos = obtenerBaseDeDatos();
                $param = array('nombre_us' => $consulta);
                $coleccion = $baseDeDatos->usuario;
                $this->objetos = $coleccion->find($param); // criterio de búsqueda
                return  $this->objetos;
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->usuario;
                $this->objetos = $coleccion->find(); // criterio de búsqueda
                return $this->objetos;
            }
        }

        public function crear($nombre, $apellido, $edad, $dui, $pass, $tipo, $avatar){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('dui_us' => $dui);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->count($param); // criterio de búsqueda

            if (!empty($this->objetos)) {
                echo 'noadd';
            }else{
                $baseDeDatos = obtenerBaseDeDatos();
                $coleccion = $baseDeDatos->usuario;
                $count =  $coleccion->count();
                $resultado = $coleccion->insertOne([
                    "id_usuario" => ($count+1),
                    "nombre_us" => $nombre,
                    "apellidos_us" => $apellido,
                    "edad" => $edad,
                    "dui_us" => $dui,
                    "contrasena_us" => $pass,
                    "us_tipo" => $tipo,
                    "avatar" => $avatar,
                    "telefono_us" => '',
                    "residencia_us" => '',
                    "correo_us" => '',
                    "sexo_us" => '',
                    "adicional_us" => '',
                ]);
                echo 'add';
            }
        }

        public function ascender($pass, int $id_ascendido, $id_usuario){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id_usuario);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    $tipo = 1;
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->updateOne(
                        ["id_usuario" => $id_ascendido],
                        [
                            '$set' => [
                                "us_tipo" => $tipo,
                            ],
                        ]
                    );
                    echo 'ascendido';
                }else{
                    echo 'noascendido';
                }
            }else{
                if ($pass == $contrasena_actual) {
                    $tipo = 1;
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->updateOne(
                        // El criterio, algo así como where
                        ["id_usuario" => $id_ascendido],
                        // Nuevos valores, no es necesario poner todos pero aquí es para ejemplificar
                        [
                            '$set' => [
                                "us_tipo" => $tipo,
                            ],
                        ]
                    );
                    echo 'ascendido';
                }else{
                    echo 'noascendido';
                }
            } 
        }

        public function descender($pass, int $id_descendido, $id_usuario){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id_usuario);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    $tipo = 2;
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->updateOne(
                        ["id_usuario" => $id_descendido],
                        [
                            '$set' => [
                                "us_tipo" => $tipo,
                            ],
                        ]
                    );
                    echo 'descendido';
                }else{
                    echo 'nodescendido';
                }
            }else{
                if ($pass == $contrasena_actual) {
                    $tipo = 2;
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->updateOne(
                        ["id_usuario" => $id_descendido],
                        [
                            '$set' => [
                                "us_tipo" => $tipo,
                            ],
                        ]
                    );
                    echo 'descendido';
                }else{
                    echo 'nodescendido';
                }
            } 
        }

        public function borrar($pass, int $id_borrado, $id_usuario){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id_usuario);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->deleteOne(
                        ["id_usuario" => $id_borrado]
                    );
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            }else{
                if ($pass == $contrasena_actual) {
                    $baseDeDatos = obtenerBaseDeDatos();
                    $coleccion = $baseDeDatos->usuario;
                    $resultado = $coleccion->deleteOne(
                        ["id_usuario" => $id_borrado]
                    );
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            } 
        }

        public function devolver_avatar($id_usuario){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id_usuario);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
            return $this->objetos;
        }

        public function verificar($email, $dui){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('correo_us' => $email, 'dui_us' => $dui);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda

            // $sql = "SELECT * FROM usuario where correo_us=:email and dui_us=:dui";
            // $query = $this->acceso->prepare($sql);
            // $query->execute(array(':email' => $email, ':dui' => $dui));
            // $this->objetos = $query->fetchall();
            if (!empty($this->objetos)) {
                if ($coleccion->count($param) == 1) {
                    echo 'encontrado';
                }else{
                    echo 'noencontrado';
                }
            }else{
                echo 'noencontrado';
            }
        }

        public function reemplazar($codigo, $email, $dui){
            $baseDeDatos = obtenerBaseDeDatos();
            $coleccion = $baseDeDatos->usuario;
            $resultado = $coleccion->updateOne(
                ["correo_us" => $email, 'dui_us' => $dui],
                [
                    '$set' => [
                        "contrasena_us" => $codigo,
                    ],
                ]
            );

            // $sql = "UPDATE usuario set contrasena_us=:codigo where correo_us=:email and dui_us=:dui";
            // $query = $this->acceso->prepare($sql);
            // $query->execute(array(':codigo' => $codigo, ':email' => $email, ':dui' => $dui));
            // echo 'reemplazado';
        }
    }