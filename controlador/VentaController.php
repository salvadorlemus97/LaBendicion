<?php
    include_once('../modelo/Venta.php');
    include_once('../modelo/Cliente.php');
    include_once('../modelo/Usuario.php');
    $venta = new Venta();
    $cliente = new Cliente();
    $usuario = new Usuario();
    session_start();
    $id_usuario = $_SESSION['usuario'];

    if ($_POST['funcion'] == 'listar') {
        $venta->buscar();
        $json = array();
        foreach ($venta->objetos as $objeto) {
            // $json['data'][] = $objeto;
            if (empty($objeto->id_cliente)) {
                $cliente_nombre = $objeto->cliente;
                $cliente_dui = $objeto->dui;
            }else{
                $cliente->buscar_datos_cliente($objeto->id_cliente);
                foreach ($cliente->objetos as $clie) {
                    $cliente_nombre = $clie->nombre.' '.$clie->apellidos;
                    $cliente_dui = $clie->dui;
                }
                
            }
            $user = $usuario->obtener_datos_id($objeto->vendedor);
            $json['data'][] = array(
                'id_venta' => $objeto->id_venta,
                'fecha' => $objeto->fecha,
                'cliente' => $cliente_nombre,
                'dui' => $cliente_dui,
                'total' => $objeto->total,
                'vendedor' => $user->nombre_us.' '.$user->apellidos_us
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'mostrar_consultas') {
        $venta_dia_vendedor=0;
        $venta->vente_dia_vendedor($id_usuario);
        foreach ($venta->objetos as $objeto) {
            $venta_dia_vendedor += $objeto->total;
        }

        $venta_diaria=0;
        $venta->venta_diaria();
        foreach ($venta->objetos as $objeto) {
            $venta_diaria += $objeto->total;
        }

        $venta_mensual=0;
        $venta->venta_mensual();
        foreach ($venta->objetos as $objeto) {
            $venta_mensual += $objeto->total;
        }

        $venta_anual=0;
        $venta->venta_anual();
        foreach ($venta->objetos as $objeto) {
            $venta_anual += $objeto->total;
        }
        $json = array();
        // foreach ($venta->objetos as $objeto) {
            $json[] = array(
                'venta_dia_vendedor' => $venta_dia_vendedor,
                'venta_diaria' => $venta_diaria,
                'venta_mensual' => $venta_mensual,
                'venta_anual' => $venta_anual
            );
        // }

        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    }