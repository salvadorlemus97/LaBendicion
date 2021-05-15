<?php
    session_start();
    if ($_SESSION['us_tipo'] == 1 || $_SESSION['us_tipo'] == 3) {
        include_once 'layouts/header.php';
    ?>        
        <title>Adm | Editar Datos</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php include_once 'layouts/nav.php'; ?>

        <!-- Modal -->
        <div class="modal fade" id="modal_formato_reporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Elegir formato de reporte</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group text-center">
                                <button id="btn_reporte" class="btn btn-outline-danger">Formato PDF<i class="far fa-file-pdf ml-2"></i> </button>
                                <button class="btn btn-outline-success ml-2">Formato Excel<i class="far fa-file-excel ml-2"></i></button>
                            </div>
                        </div>                        
                    </div>   
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="crear_lote" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Crear lote</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success text-center" id="add-lote" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se agrego correctamente</span>
                            </div>

                            <form id="form-crear-lote">
                                <div class="input-group mb-3" title="Nombre del producto">
                                    <!-- <label for="concentracion">Concentracion</label> -->
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <label id="nombre_producto_lote" class="form-control">Nombre de producto</label>
                                </div>
                                
                                <div class="input-group mb-3" title="Seleccionar proveedor">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                    </div>
                                    <select name="proveedor" id="proveedor" class="form-control select2" style="width: 90%"></select>
                                </div>

                                <div class="input-group mb-3" title="Ingresar Stock">
                                    <!-- <label for="concentracion">Concentracion</label> -->
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                    </div>
                                    <input id="stock" type="number" class="form-control" placeholder="Ingrese Stock">
                                </div>

                                <div class="input-group mb-3" title="Ingresar fecha de vencimiento">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input id="vencimiento" type="date" class="form-control" placeholder="Ingrese fecha de vencimiento">
                                </div>                                
                                <input type="hidden" id="id_lote_prod">
                                <div class="card-footer">
                                    <button type="submit" class="btn bg-gradient-primary float-right m-1">Guardar</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
                                </div>
                            </form>
                        </div>                        
                    </div>   
                </div>
            </div>
        </div>

        <div class="modal fade" id="cambio_logo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cambiar logo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img id="logoactual" src="../img/avatar.png" alt="avatar" class="profile-user-img img-fluid img-circle">
                        </div>
                        <div class="text-center">
                            <b id="nombre_logo"></b>
                        </div>
                        <div class="alert alert-success text-center" id="edit" style="display:none">
                            <span><i class="fas fa-check m-1"></i>El logo se edito </span>
                        </div>
                        <div class="alert alert-danger text-center" id="noedit" style="display:none">
                            <span><i class="fas fa-times m-1"></i>Formato no soportado</span>
                        </div>
                        <form id="form-logo" enctype="multipart/form-data">
                            <div class="input-group mb-3 ml-5 mt-2">
                               <input type="file" class="input-group" name="foto">
                               <input type="hidden" name="funcion" id="funcion">
                               <input type="hidden" name="id_logo_prod" id="id_logo_prod">
                               <input type="hidden" name="avatar" id="avatar">
                            </div>                             
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn bg-gradient-primary">Guardar</button>
                            </div>                       
                        </form>
                    </div>                    
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="crear_producto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Crear producto</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success text-center" id="add" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se agrego correctamente</span>
                            </div>
                            <div class="alert alert-danger text-center" id="noadd" style="display:none">
                                <span><i class="fas fa-times m-1"></i>El producto ya existe</span>
                            </div>
                            <div class="alert alert-success text-center" id="edit_prod" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se edito correctamente</span>
                            </div>
                            <form id="form-crear-producto">
                                <div class="input-group mb-3" title="Ingresar nombre del producto">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                    </div>
                                    <input id="nombre_producto" required type="text" class="form-control" placeholder="Ingrese nombre">
                                </div>
                                <div class="input-group mb-3" title="Ingresar concentracion">
                                    <!-- <label for="concentracion">Concentracion</label> -->
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-mortar-pestle"></i></span>
                                    </div>
                                    <input id="concentracion" type="text" class="form-control" placeholder="Ingrese concentracion">
                                </div>
                                <div class="input-group mb-3" title="Ingresar informacion adicional">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-prescription-bottle-alt"></i></span>
                                    </div>
                                    <input id="adicional" type="text" class="form-control" placeholder="Ingrese adicional">
                                </div>
                                <div class="input-group mb-3" title="Ingresar precio del producto">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input step="any" id="precio" required type="number" value="1.00" class="form-control" placeholder="Ingrese precio">
                                </div>
                                <div class="input-group mb-3" title="Seleccionar laboratorio">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flask"></i></span>
                                    </div>
                                    <select name="laboratorio" id="laboratorio" class="form-control select2" style="width: 90%"></select>
                                </div>
                                <div class="input-group mb-3" title="Seleccionar tipo de producto">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                                    </div>
                                    <select name="tipo" id="tipo" class="form-control select2" style="width: 90%"></select>
                                </div>
                                <div class="input-group mb-3" title="Seleccionar presentacion del producto">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pills"></i></span>
                                    </div>
                                    <select name="presentacion" id="presentacion" class="form-control select2" style="width: 90%"></select>
                                </div>
                                <input type="hidden" id="id_edit_prod">
                                <div class="card-footer">
                                    <button type="submit" class="btn bg-gradient-primary float-right m-1">Guardar</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
                                </div>
                            </form>
                        </div>                        
                    </div>   
                </div>
            </div>
        </div>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1>
                            <!-- id="btn_reporte" -->
                                Gestion productos
                                <button id="btn_crear" type="button" data-toggle="modal" data-target="#crear_producto" class="btn bg-gradient-primary ml-2">Crear producto</button>
                                <button type="button" data-toggle="modal" data-target="#modal_formato_reporte" class="btn bg-gradient-success ml-2">Reportes de producto</button>
                            </h1>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="adm_catalogo.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Gestion productos</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section> 
            <section>
                <div class="container-fluid">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Buscar producto</h3>
                            <div class="input-group">
                                <input id="buscar-producto" placeholder="Ingrese nombre de producto" type="text" class="form-control float-left">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="productos" class="row d-flex align-items-stretch"></div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </section>           
        </div>
        <!-- /.content-wrapper --> 
    <?php
        include_once 'layouts/footer.php';
    }else{
        header('Location: ../index.php');
    }
?>
<script src="../js/producto.js"></script>