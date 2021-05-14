<?php
    include_once 'Conexion.php';

    class Usuario{
        var $objetos;
        var $acceso;
       

        public function Loguearse(int $dui, $pass){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('dui_us' => $dui);
            $coleccion = $baseDeDatos->usuario;
            $objetoss = $coleccion->find(array('dui_us' => $dui));
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
            $objetoss = $coleccion->find($param);
            return $objetoss;
        }

        public function obtener_datos(int $id){
            $baseDeDatos = obtenerBaseDeDatos();
            $param = array('id_usuario' => $id);
            $coleccion = $baseDeDatos->usuario;
            $this->objetos = $coleccion->find($param); // criterio de búsqueda
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
            $sql = 'UPDATE usuario 
                set telefono_us=:telefono, residencia_us=:residencia, correo_us=:correo, sexo_us=:sexo, adicional_us=:adicional 
                where id_usuario=:id_usuario';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario, ':telefono' => $telefono, ':residencia' => $residencia, ':correo' => $correo, ':sexo' => $sexo, ':adicional' => $adicional));
            
        }

        // public function cambiar_contra($id_usuario, $oldpass, $newpass){
        //     $sql = 'SELECT * FROM usuario where id_usuario=:id and contrasena_us=:oldpass';
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':id' => $id_usuario, ':oldpass' => $oldpass));
        //     $this->objetos = $query->fetchall();
        //     if (!empty($this->objetos)) {
        //         $sql = 'UPDATE usuario set contrasena_us=:newpass where id_usuario=:id';
        //         $query = $this->acceso->prepare($sql);
        //         $query->execute(array(':id' => $id_usuario, ':newpass' => $newpass));
        //         echo 'update';
        //     }else{
        //         echo 'noupdate';
        //     }
        // }

        public function cambiar_contra($id_usuario, $oldpass, $newpass){
            $sql = 'SELECT * FROM usuario where id_usuario=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_usuario));
            $this->objetos = $query->fetchall();
            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($oldpass, $contrasena_actual)) {
                    $pass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 10]);
                    $sql = 'UPDATE usuario set contrasena_us=:newpass where id_usuario=:id';
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_usuario, ':newpass' => $pass));
                    echo 'update';
                }else{
                    echo 'noupdate';
                }
            }else{
                if ($oldpass == $contrasena_actual) {
                    $pass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 10]);
                    $sql = 'UPDATE usuario set contrasena_us=:newpass where id_usuario=:id';
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_usuario, ':newpass' => $pass));
                    echo 'update';
                }else{
                    echo 'noupdate';
                }
            }
        }

        public function cambiar_foto($id_usuario, $nombre){
            $sql = 'SELECT avatar FROM usuario where id_usuario=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_usuario));
            $this->objetos = $query->fetchall();
            
            $sql = 'UPDATE usuario set avatar=:nombre where id_usuario=:id';
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id_usuario, ':nombre' => $nombre));
            return $this->objetos;
            
        }

        public function buscar(){
            if (!empty($_POST['consulta'])) {
                $consulta = $_POST['consulta'];
                $sql = 'SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us where nombre_us LIKE :consulta';
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':consulta' => "%$consulta%"));
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }else{
                $sql = "SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us where nombre_us not like '' order by id_usuario limit 25";
                $query = $this->acceso->prepare($sql);
                $query->execute();
                $this->objetos = $query->fetchall();
                return $this->objetos;
            }
        }

        public function crear($nombre, $apellido, $edad, $dui, $pass, $tipo, $avatar){
            $sql = "SELECT id_usuario FROM usuario where dui_us=:dui";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':dui' => $dui));
            $this->objetos = $query->fetchall();

            if (!empty($this->objetos)) {
                echo 'noadd';
            }else{
                $sql = "INSERT INTO usuario(nombre_us, apellidos_us, edad, dui_us, contrasena_us, us_tipo, avatar) 
                    values(:nombre, :apellido, :edad, :dui, :pass, :tipo, :avatar)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':edad' => $edad, ':dui' => $dui, ':pass' => $pass, ':tipo' => $tipo, ':avatar' => $avatar));
                echo 'add';
            }
        }

        // public function ascender($pass, $id_ascendido, $id_usuario){
        //     $sql = "SELECT id_usuario FROM usuario where id_usuario=:id_usuario and contrasena_us=:pass";
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':id_usuario' => $id_usuario, ':pass' => $pass));
        //     $this->objetos = $query->fetchall();

        //     if (!empty($this->objetos)) {
        //         $tipo = 1;
        //         $sql = "UPDATE usuario set us_tipo=:tipo where id_usuario=:id";
        //         $query = $this->acceso->prepare($sql);
        //         $query->execute(array(':id' => $id_ascendido, ':tipo' => $tipo));
        //         echo 'ascendido';
        //     }else{
        //         echo 'noascendido';
        //     }
        // }

        public function ascender($pass, $id_ascendido, $id_usuario){
            $sql = "SELECT * FROM usuario where id_usuario=:id_usuario";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario));
            $this->objetos = $query->fetchall();

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    $tipo = 1;
                    $sql = "UPDATE usuario set us_tipo=:tipo where id_usuario=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_ascendido, ':tipo' => $tipo));
                    echo 'ascendido';
                }else{
                    echo 'noascendido';
                }
            }else{
                if ($pass == $contrasena_actual) {
                    $tipo = 1;
                    $sql = "UPDATE usuario set us_tipo=:tipo where id_usuario=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_ascendido, ':tipo' => $tipo));
                    echo 'ascendido';
                }else{
                    echo 'noascendido';
                }
            } 
        }

        // public function descender($pass, $id_descendido, $id_usuario){
        //     $sql = "SELECT id_usuario FROM usuario where id_usuario=:id_usuario and contrasena_us=:pass";
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':id_usuario' => $id_usuario, ':pass' => $pass));
        //     $this->objetos = $query->fetchall();

        //     if (!empty($this->objetos)) {
        //         $tipo = 2;
        //         $sql = "UPDATE usuario set us_tipo=:tipo where id_usuario=:id";
        //         $query = $this->acceso->prepare($sql);
        //         $query->execute(array(':id' => $id_descendido, ':tipo' => $tipo));
        //         echo 'descendido';
        //     }else{
        //         echo 'nodescendido';
        //     }
        // }

        public function descender($pass, $id_descendido, $id_usuario){
            $sql = "SELECT * FROM usuario where id_usuario=:id_usuario";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario));
            $this->objetos = $query->fetchall();

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    $tipo = 2;
                    $sql = "UPDATE usuario set us_tipo=:tipo where id_usuario=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_descendido, ':tipo' => $tipo));
                    echo 'descendido';
                }else{
                    echo 'nodescendido';
                }
            }else{
                if ($pass == $contrasena_actual) {
                    $tipo = 2;
                    $sql = "UPDATE usuario set us_tipo=:tipo where id_usuario=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_descendido, ':tipo' => $tipo));
                    echo 'descendido';
                }else{
                    echo 'nodescendido';
                }
            } 
        }

        // public function borrar($pass, $id_borrado, $id_usuario){
        //     $sql = "SELECT id_usuario FROM usuario where id_usuario=:id_usuario and contrasena_us=:pass";
        //     $query = $this->acceso->prepare($sql);
        //     $query->execute(array(':id_usuario' => $id_usuario, ':pass' => $pass));
        //     $this->objetos = $query->fetchall();
        //     if (!empty($this->objetos)) {
        //         $sql = "DELETE FROM usuario where id_usuario=:id";
        //         $query = $this->acceso->prepare($sql);
        //         $query->execute(array(':id' => $id_borrado));
        //         echo 'borrado';
        //     }else{
        //         echo 'noborrado';
        //     }
        // }

        public function borrar($pass, $id_borrado, $id_usuario){
            $sql = "SELECT * FROM usuario where id_usuario=:id_usuario";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario));
            $this->objetos = $query->fetchall();

            foreach ($this->objetos as $objeto) {
                $contrasena_actual = $objeto->contrasena_us;
            }
            if (strpos($contrasena_actual,'$2y$10$') === 0) {
                if (password_verify($pass, $contrasena_actual)) {
                    $sql = "DELETE FROM usuario where id_usuario=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_borrado));
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            }else{
                if ($pass == $contrasena_actual) {
                    $sql = "DELETE FROM usuario where id_usuario=:id";
                    $query = $this->acceso->prepare($sql);
                    $query->execute(array(':id' => $id_borrado));
                    echo 'borrado';
                }else{
                    echo 'noborrado';
                }
            } 
        }

        public function devolver_avatar($id_usuario){
            $sql = "SELECT avatar FROM usuario where id_usuario=:id_usuario";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario' => $id_usuario));
            $this->objetos = $query->fetchall();
            return $this->objetos;
        }

        public function verificar($email, $dui){
            $sql = "SELECT * FROM usuario where correo_us=:email and dui_us=:dui";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':email' => $email, ':dui' => $dui));
            $this->objetos = $query->fetchall();
            if (!empty($this->objetos)) {
                if ($query->rowCount() == 1) {
                    echo 'encontrado';
                }else{
                    echo 'noencontrado';
                }
            }else{
                echo 'noencontrado';
            }
        }

        public function reemplazar($codigo, $email, $dui){
            $sql = "UPDATE usuario set contrasena_us=:codigo where correo_us=:email and dui_us=:dui";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':codigo' => $codigo, ':email' => $email, ':dui' => $dui));
            // echo 'reemplazado';
        }
    }