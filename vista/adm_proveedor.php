<?php
    session_start();
    if ($_SESSION['us_tipo'] == 1 || $_SESSION['us_tipo'] == 3) {
        include_once 'layouts/header.php';
    ?>        
        <title>Adm | Editar Datos</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php include_once 'layouts/nav.php'; ?>

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
                        <div class="alert alert-success text-center" id="edit-prov" style="display:none">
                            <span><i class="fas fa-check m-1"></i>El logo se edito </span>
                        </div>
                        <div class="alert alert-danger text-center" id="noedit-prov" style="display:none">
                            <span><i class="fas fa-times m-1"></i>Formato no soportado</span>
                        </div>
                        <form id="form-logo" enctype="multipart/form-data">
                            <div class="input-group mb-3 ml-5 mt-2">
                               <input type="file" class="input-group" name="foto">
                               <input type="hidden" name="funcion" id="funcion">
                               <input type="hidden" name="id_logo_prov" id="id_logo_prov">
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
        <div class="modal fade" id="crear_proveedor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Crear proveedor</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success text-center" id="add-prov" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se agrego correctamente</span>
                            </div>
                            <div class="alert alert-danger text-center" id="noadd-prov" style="display:none">
                                <span><i class="fas fa-times m-1"></i>El proveedor ya existe</span>
                            </div>
                            <div class="alert alert-success text-center" id="edit-prove" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se modifico correctamente</span>
                            </div>
                            <form id="form-crear">
                                <div class="form-group">
                                    <label for="nombre">Nombres</label>
                                    <input id="nombre" required type="text" class="form-control" placeholder="Ingrese nombre">
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Telefono</label>
                                    <input id="telefono" required type="text" class="form-control" placeholder="Ingrese telefono">
                                </div>
                                <div class="form-group">
                                    <label for="correo">Correo</label>
                                    <input id="correo" type="email" class="form-control" placeholder="Ingrese correo">
                                </div>
                                <div class="form-group">
                                    <label for="direccion">Direccion</label>
                                    <input id="direccion" required type="text" class="form-control" placeholder="Ingrese direccion">
                                </div>
                                <input type="hidden" id="id_edit_prov">
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
                        <div class="col-sm-6">
                            <h1>
                                Gestion proveedor
                                <button type="button" data-toggle="modal" data-target="#crear_proveedor" class="btn bg-gradient-primary ml-2">Crear proveedor</button>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="adm_catalogo.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Gestion proveedor</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section> 
            <section>
                <div class="container-fluid">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Buscar proveedor</h3>
                            <div class="input-group">
                                <input id="buscar_proveedor" placeholder="Ingrese nombre de proveedor" type="text" class="form-control float-left">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="proveedores" class="row d-flex align-items-stretch"></div>
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
<script src="../js/proveedor.js"></script>