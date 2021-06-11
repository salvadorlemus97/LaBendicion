<?php
    include_once('../modelo/VentaProducto.php');
    include '../modelo/Producto.php';
    include '../modelo/Laboratorio.php';
    include '../modelo/Tipo.php';
    include '../modelo/Presentacion.php';
    $venta_producto = new VentaProducto();
    $producto = new Producto();
    $laboratorio_l = new Laboratorio();
    $tipo_prod = new Tipo();
    $presentacion_p = new Presentacion();

    if ($_POST['funcion'] == 'ver') {
        $id = $_POST['id'];
        $venta_producto->ver($id);
        $json = array();
        foreach ($venta_producto->objetos as $objeto) {
            $prod = $producto->obtener_prod_id($objeto->producto_id_producto);
            $lab = $laboratorio_l->obtener_lab_id($prod->prod_lab);
            $tp = $tipo_prod->obtener_tipoprod_id($prod->prod_tip_prod);
            $present = $presentacion_p->obtener_present_id($prod->prod_present);

            $json[] = array(
                'precio' => $objeto->precio,
                'cantidad' => $objeto->cantidad,
                'producto' => $prod->nombre,
                'concentracion' => $prod->concentracion,
                'adicional' => $prod->adicional,
                'subtotal' => $objeto->subtotal,
                'laboratorio' => $lab->nombre,
                'tipo' => $tp->nombre,
                'presentacion' => $present->nombre
            );
        }

        $jsonstring = json_encode($json);
        echo $jsonstring;
    }