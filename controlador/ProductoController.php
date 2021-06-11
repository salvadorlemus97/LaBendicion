<?php
    include '../modelo/Producto.php';
    include '../modelo/Laboratorio.php';
    include '../modelo/Tipo.php';
    include '../modelo/Presentacion.php';
    require_once('../vendor/autoload.php');
    $producto = new Producto();
    $laboratorio_l = new Laboratorio();
    $tipo_prod = new Tipo();
    $presentacion_p = new Presentacion();

    if ($_POST['funcion'] == 'crear') {
        $nombre = $_POST['nombre'];
        $concentracion = $_POST['concentracion'];
        $adicional = $_POST['adicional'];
        $precio = $_POST['precio'];
        $laboratorio = $_POST['laboratorio'];
        $tipo = $_POST['tipo'];
        $presentacion = $_POST['presentacion'];
        $avatar = 'prod_default.png';

        $producto->crear($nombre, $avatar, $concentracion, $adicional, $precio, $laboratorio, $tipo, $presentacion);
    }

    if ($_POST['funcion'] == 'buscar') {
        $producto->buscar();

        $json = array();
        foreach ($producto->objetos as $objeto) {
            $producto->obtener_stock($objeto->id_producto);
            $total=0;
            foreach ($producto->objetos as $obj) {
                $total += $obj->stock;
            }
            $lab = $laboratorio_l->obtener_lab_id($objeto->prod_lab);
            $tp = $tipo_prod->obtener_tipoprod_id($objeto->prod_tip_prod);
            $present = $presentacion_p->obtener_present_id($objeto->prod_present);
            $json[] = array(
                'id' => $objeto->id_producto,
                'nombre' => $objeto->nombre,
                'concentracion' => $objeto->concentracion,
                'adicional' => $objeto->adicional,
                'precio' => $objeto->precio,
                'stock' => $total,
                'laboratorio' => $lab->nombre,
                'tipo' => $tp->nombre,
                'presentacion' => $present->nombre,
                'laboratorio_id' => $objeto->prod_lab,
                'tipo_id' => $objeto->prod_tip_prod,
                'presentacion_id' => $objeto->prod_present,
                'avatar' => '../img/prod/'.$objeto->avatar
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'cambiar_avatar') {
        $id = $_POST['id_logo_prod'];
        $avatar = $_POST['avatar'];
        if (($_FILES['foto']['type'] == 'image/jpeg') || ($_FILES['foto']['type'] == 'image/png') || ($_FILES['foto']['type'] == 'image/gif')) {
            $nombre = uniqid().'-'.$_FILES['foto']['name'];
            $ruta = '../img/prod/'.$nombre;
            move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
            $producto->cambiar_logo($id, $nombre);
            if($avatar != '../img/prod/prod_default.png'){
                unlink($avatar);
            }
            $json = array();
            $json[] = array(
                'ruta' => $ruta,
                'alert' => 'edit'
            );
            $jsonstring = json_encode($json[0]);
            echo $jsonstring;
        }else{
            $json = array();
            $json[] = array(
                'alert' => 'noedit'
            );
            $jsonstring = json_encode($json[0]);
            echo $jsonstring;
        }
    }

    if ($_POST['funcion'] == 'editar') {
        $nombre = $_POST['nombre'];
        $id = $_POST['id'];
        $concentracion = $_POST['concentracion'];
        $adicional = $_POST['adicional'];
        $precio = $_POST['precio'];
        $laboratorio = $_POST['laboratorio'];
        $tipo = $_POST['tipo'];
        $presentacion = $_POST['presentacion'];

        $producto->editar($id, $nombre, $concentracion, $adicional, $precio, $laboratorio, $tipo, $presentacion);
    }

    if ($_POST['funcion'] == 'borrar') {
        $id = $_POST['id'];
        $producto->borrar($id);
    }

    if ($_POST['funcion'] == 'buscar_id') {
        $id = $_POST['id_producto'];
        $producto->buscar_id($id);

        $json = array();
        foreach ($producto->objetos as $objeto) {
            $producto->obtener_stock($objeto->id_producto);
            $total=0;
            foreach ($producto->objetos as $obj) {
                $total += $obj->stock;
            }
            $lab = $laboratorio_l->obtener_lab_id($objeto->prod_lab);
            $tp = $tipo_prod->obtener_tipoprod_id($objeto->prod_tip_prod);
            $present = $presentacion_p->obtener_present_id($objeto->prod_present);
            $json[] = array(
                'id' => $objeto->id_producto,
                'nombre' => $objeto->nombre,
                'concentracion' => $objeto->concentracion,
                'adicional' => $objeto->adicional,
                'precio' => $objeto->precio,
                'stock' => $total,
                'laboratorio' => $lab->nombre,
                'tipo' => $tp->nombre,
                'presentacion' => $present->nombre,
                'laboratorio_id' => $objeto->prod_lab,
                'tipo_id' => $objeto->prod_tip_prod,
                'presentacion_id' => $objeto->prod_present,
                'avatar' => '../img/prod/'.$objeto->avatar
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'verificar_stock') {
        $error = 0;
        $productos = json_decode($_POST['productos']);
        foreach ($productos as $objeto) {
            $producto->obtener_stock($objeto->id);
            $total = 0;
            foreach ($producto->objetos as $obj) {
                $total += $obj->stock;
            }
            if ($total >= $objeto->cantidad && $objeto->cantidad > 0) {
                $error = ($error + 0);
            }else{
                $error = ($error + 1);
            }
        }
        echo $error;
    }

    if ($_POST['funcion'] == 'traer_productos') {
        $html = '';
        $productos = json_decode($_POST['productos']);
        foreach ($productos as $resultado) {
            $producto->buscar_id($resultado->id);
            foreach ($producto->objetos as $objeto) {
                $subtotal = ($objeto->precio * $resultado->cantidad);
                $producto->obtener_stock($objeto->id_producto);
                $total = 0;
                foreach ($producto->objetos as $obj) {
                    $total += $obj->stock;
                }
                $lab = $laboratorio_l->obtener_lab_id($objeto->prod_lab);
                $tp = $tipo_prod->obtener_tipoprod_id($objeto->prod_tip_prod);
                $present = $presentacion_p->obtener_present_id($objeto->prod_present);
                $html .= "
                    <tr prodid='$objeto->id_producto' prodprecio='$objeto->precio'>
                        <td>$objeto->nombre</td>
                        <td>$total</td>
                        <td class='precio'>$objeto->precio</td>
                        <td>$objeto->concentracion</td>
                        <td>$objeto->adicional</td>
                        <td>$lab->nombre</td>
                        <td>$present->nombre</td>
                        <td><input type='number' min='1' class='form-control cantidad_producto' value='$resultado->cantidad'></td>
                        <td class='subtotales'><h5>$subtotal</h5></td>
                        <td><button class='btn btn-danger borrar-producto'><i class='fas fa-times-circle'></i></button></td>
                    </tr>
                ";
            }
        }
        echo $html;
    }

    if ($_POST['funcion'] == 'reporte_producto') {
        // date_default_timezone_get('America/El_Salvador');
        $fecha = date('Y-m-d H:i:s');
        $html = '
            <header>
                <div>
                    <img src="../img/logo.png" width="60" height="60">
                </div>
                <h1>Reporte de productos</h1>
                <div id="project">
                    <div>
                        <span>Fecha y Hora: </span>'.$fecha.'
                    </div>
                </div>
            </header>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Producto</th>
                        <th>Concentracion</th>
                        <th>Adicional</th>
                        <th>Laboratorio</th>
                        <th>Presentacion</th>
                        <th>Tipo</th>
                        <th>Stock</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
        ';
        $producto->reporte_producto();
        $contador = 0;
        foreach ($producto->objetos as $objeto) {
            $contador++;
            $producto->obtener_stock($objeto->id_producto);
            $total = 0;
            foreach ($producto->objetos as $obj) {
                $total += $obj->stock;
            }
            $lab = $laboratorio_l->obtener_lab_id($objeto->prod_lab);
            $tp = $tipo_prod->obtener_tipoprod_id($objeto->prod_tip_prod);
            $present = $presentacion_p->obtener_present_id($objeto->prod_present);
            $html .= '
                <tr>
                    <td class="servic">'.$contador.'</td>
                    <td class="servic">'.$objeto->nombre.'</td>
                    <td class="servic">'.$objeto->concentracion.'</td>
                    <td class="servic">'.$objeto->adicional.'</td>
                    <td class="servic">'.$lab->nombre.'</td>
                    <td class="servic">'.$present->nombre.'</td>
                    <td class="servic">'.$tp->nombre.'</td>
                    <td class="servic">'.$total.'</td>
                    <td class="servic">'.$objeto->precio.'</td>
                </tr>
            ';
        }
        $html .= '
                </tbody>
            </table>
        ';
        // $css = file_get_contents('../css/pdf.css');
        $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output('../pdf/pdf-'.$_POST['funcion'].'.pdf', 'F');
    }