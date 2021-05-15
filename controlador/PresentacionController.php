<?php
    include_once '../modelo/Presentacion.php';
    $Presentacion = new Presentacion();

    if ($_POST['funcion'] == 'crear') {
        $nombre = $_POST['nombre_presentacion'];

        $Presentacion->crear($nombre);
    }

    if ($_POST['funcion'] == 'buscar') {
        $Presentacion->buscar();

        $json = array();
        foreach ($Presentacion->objetos as $objeto) {
            $json[] = array(
                'id' => $objeto->id_presentacion,
                'nombre' => $objeto->nombre
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'borrar') {
        $id = $_POST['id'];
        $Presentacion->borrar($id);
    }

    if ($_POST['funcion'] == 'editar') {
        $nombre = $_POST['nombre_presentacion'];
        $id_editado = $_POST['id_editado'];

        $Presentacion->editar($nombre, $id_editado);
    }

    if ($_POST['funcion'] == 'rellenar_presentaciones') {
        $Presentacion->rellenar_presentaciones();

        $json = array();
        foreach ($Presentacion->objetos as $objeto) {
            $json[] = array(
                'id' => $objeto->id_presentacion,
                'nombre' => $objeto->nombre
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;
    }