<?php
    include '../modelo/Venta.php';
    include_once('../modelo/Conexion.php');
    $venta = new Venta();
    session_start();
    $vendedor = $_SESSION['usuario'];

    if ($_POST['funcion'] == 'registrar_compra') {
        $total = $_POST['total'];
        $cliente = $_POST['cliente'];
        $productos = json_decode($_POST['json']);
        date_default_timezone_set('America/El_Salvador');
        $fecha = date('Y-m-d H:i:s');
        $uv = $venta->crear($cliente, $total, $fecha, $vendedor);
        $id_venta = $uv+1;

        try {
            foreach ($productos as $prod) {
                $cantidad = $prod->cantidad;

                    $baseDeDatoss = obtenerBaseDeDatos();
                    $pid = intval($prod->id);
                    $p = array('lote_id_prod'=>$pid);
                    $coleccions = $baseDeDatoss->lote;
                    $lote = $coleccions->findOne($p); // criterio de búsqueda

                    $baseDeDatosss = obtenerBaseDeDatos();
                    $coleccionss = $baseDeDatosss->detalle_venta;
                    $count =  $coleccionss->count();
                    $resultado = $coleccionss->insertOne([
                        "id_detalle" => ($count+1),
                        "det_cantidad" => $cantidad,
                        "det_vencimiento" => $lote->vencimiento,
                        "id__det_lote" => $lote->id_lote,
                        "id__det_prod" => $prod->id,
                        "lote_id_prov" => $lote->lote_id_prov,
                        "id_det_venta" => $id_venta,
                    ]);
                            
                    $baseDeDatossss = obtenerBaseDeDatos();
                    $coleccionssss = $baseDeDatossss->lote;
                    $lot = $coleccionssss->findOne(array('id_lote' => $lote->id_lote));
                    $cn = ($lot->stock - $cantidad);
                    $resultado = $coleccionssss->updateOne(
                        ["id_lote" => $lote->id_lote],
                        [
                            '$set' => [
                                "stock" => $cn,
                            ],
                        ]
                    );

                $subtotal = ($prod->cantidad * $prod->precio);
                $baseDeDat = obtenerBaseDeDatos();
                $colecci = $baseDeDat->venta_producto;
                $count =  $colecci->count();
                $resultado = $colecci->insertOne([
                    "id_ventaproducto" => ($count+1),
                    "precio" => $prod->precio,
                    "cantidad" => $prod->cantidad,
                    "subtotal" => $subtotal,
                    "producto_id_producto" => $prod->id,
                    "venta_id_venta" => $id_venta,
                ]);
            }
            // $conexion->commit();
        } catch (Exception $error) {            
            // $conexion->rollBack();
            $venta->borrar($id_venta);
            echo $error->getMessage();
        }
    }