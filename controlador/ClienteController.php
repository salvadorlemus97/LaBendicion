<?php
	include_once('../modelo/Cliente.php');
	$cliente = new Cliente();

	if ($_POST['funcion'] == 'buscar') {
        $cliente->buscar();
        date_default_timezone_set('America/El_Salvador');
        $fecha = date('Y-m-d H:i:s');
        $fecha_actual = new DateTime($fecha);
        
        $json = array();
        foreach ($cliente->objetos as $objeto) {
        	$nac = new DateTime($objeto->edad);
        	$edad = $nac->diff($fecha_actual);
        	$edad_y = $edad->y;
            $json[] = array(
                'id' => $objeto->id_cliente,
                'nombre' => $objeto->nombre.' '.$objeto->apellidos,
                'dui' => $objeto->dui,
                'edad' => $edad_y,
                'telefono' => $objeto->telefono,
                'correo' => $objeto->correo,
                'sexo' => $objeto->sexo,
                'adicional' => $objeto->adicional,
                'avatar' => '../img/cliente/'.$objeto->avatar
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'crear') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dui = $_POST['dui'];
        $nacimiento = $_POST['nacimiento'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $sexo = $_POST['sexo'];
        $adicional = $_POST['adicional'];
        $avatar = 'default.jpg';

        $cliente->crear($nombre, $apellido, $dui, $nacimiento, $telefono, $correo, $sexo, $adicional, $avatar);
    }

    if ($_POST['funcion'] == 'editar') {
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $id = $_POST['id'];
        $adicional = $_POST['adicional'];

        $cliente->editar($telefono, $correo, $id, $adicional);
    }

    if ($_POST['funcion'] == 'borrar') {
        $id = $_POST['id'];
        $cliente->borrar($id);
    }

    if ($_POST['funcion'] == 'rellenar_clientes') {
        $cliente->rellenar_clientes();
        $json = array();
        foreach ($cliente->objetos as $objeto) {
            $json[] = array(
                'id' => $objeto->id_cliente,
                'nombre' => $objeto->nombre.' '.$objeto->apellidos.' | '.$objeto->dui
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

