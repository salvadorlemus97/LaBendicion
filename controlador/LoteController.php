<?php
    include_once('../modelo/Lote.php');
    include '../modelo/Proveedor.php';
    include '../modelo/Producto.php';
    include '../modelo/Laboratorio.php';
    include '../modelo/Tipo.php';
    include '../modelo/Presentacion.php';
    $proveedor = new Proveedor();
    $producto = new Producto();
    $laboratorio_l = new Laboratorio();
    $tipo_prod = new Tipo();
    $presentacion_p = new Presentacion();
    $lote = new Lote();

    if ($_POST['funcion'] == 'crear') {
        $id_producto = $_POST['id_producto'];
        $proveedor = $_POST['proveedor'];
        $stock = $_POST['stock'];
        $vencimiento = $_POST['vencimiento'];

        $lote->crear($id_producto, $proveedor, $stock, $vencimiento);
    }

    if ($_POST['funcion'] == 'buscar') {
        $lote->buscar();
        $json = array();
        date_default_timezone_set('America/EL_Salvador');
        $fecha = date('Y-m-d H:i:s');
        $fecha_actual = new DateTime($fecha);
        foreach ($lote->objetos as $objeto) {
            $vencimiento = new DateTime($objeto->vencimiento);
            $diferencia = $vencimiento->diff($fecha_actual);
            $mes = $diferencia->m;
            $dia = $diferencia->d;
            $verificado = $diferencia->invert;
            if ($verificado == 0) {
                $estado = 'danger';
                $mes = $mes*(-1);
                $dia = $dia*(-1);
            }else{
                if($mes > 3){
                    $estado = 'light';
                }
                if($mes <= 3){
                    $estado = 'warning';
                }
            }
            $prov = $proveedor->obtener_prov_id($objeto->lote_id_prov);
            $prod = $producto->obtener_prod_id($objeto->lote_id_prod);
            $lab = $laboratorio_l->obtener_lab_id($prod->prod_lab);
            $tp = $tipo_prod->obtener_tipoprod_id($prod->prod_tip_prod);
            $present = $presentacion_p->obtener_present_id($prod->prod_present);
            $json[] = array(
                'id' => $objeto->id_lote,
                'nombre' => $prod->nombre,
                'concentracion' => $prod->concentracion,
                'adicional' => $prod->adicional,
                'vencimiento' => $objeto->vencimiento,
                'proveedor' => $prov->nombre,
                'stock' => $objeto->stock,
                'laboratorio' => $lab->nombre,
                'tipo' => $tp->nombre,
                'presentacion' => $present->nombre,
                'avatar' => '../img/prod/'.$prod->avatar,
                'mes' => $mes,
                'dia' => $dia,
                'estado' => $estado
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'editar') {
        $id_lote = $_POST['id'];
        $stock = $_POST['stock'];

        $lote->editar($id_lote, $stock);
    }

    if ($_POST['funcion'] == 'borrar') {
        $id_lote = $_POST['id'];

        $lote->borrar($id_lote);
    }