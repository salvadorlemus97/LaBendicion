<?php
    session_start();
    if ($_SESSION['us_tipo'] == 1 || $_SESSION['us_tipo'] == 3) {
        include_once 'layouts/header.php';
    ?>        
        <title>Adm | Editar Datos</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php include_once 'layouts/nav.php'; ?>

        <!-- Modal crear -->
        <div class="modal fade" id="crear_cliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Crear cliente</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success text-center" id="add-clie" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se agrego correctamente</span>
                            </div>
                            <div class="alert alert-danger text-center" id="noadd-clie" style="display:none">
                                <span><i class="fas fa-times m-1"></i>El cliente ya existe</span>
                            </div>
                            <form id="form-crear">
                                <div class="form-group">
                                    <label for="nombre">Nombres</label>
                                    <input id="nombre" required type="text" class="form-control" placeholder="Ingrese nombre">
                                </div>
                                <div class="form-group">
                                    <label for="apellido">Apellidos</label>
                                    <input id="apellido" required type="text" class="form-control" placeholder="Ingrese Apellidos">
                                </div>
                                <div class="form-group">
                                    <label for="dui">DUI</label>
                                    <input id="dui" required type="text" class="form-control" placeholder="Ingrese DUI">
                                </div>
                                <div class="form-group">
                                    <label for="nacimiento">Edad</label>
                                    <input id="nacimiento" required type="date" class="form-control" placeholder="Ingrese fecha de nacimiento">
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
                                    <label for="sexo">Sexo</label>
                                    <input id="sexo" required type="text" class="form-control" placeholder="Ingrese sexo">
                                </div>
                                <div class="form-group">
                                    <label for="adicional">Adicional</label>
                                    <input id="adicional" required type="text" class="form-control" placeholder="Ingrese informacion adicional">
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

        <!-- Modal editar -->
        <div class="modal fade" id="editar_cliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Editar cliente</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success text-center" id="edit-clie" style="display:none">
                                <span><i class="fas fa-check m-1"></i>Se edito correctamente</span>
                            </div>
                            <div class="alert alert-danger text-center" id="noedit-clie" style="display:none">
                                <span><i class="fas fa-times m-1"></i>Nose pudo editar</span>
                            </div>
                            <form id="form-editar">
                                <div class="form-group">
                                    <label for="telefono_edit">Telefono</label>
                                    <input id="telefono_edit" required type="text" class="form-control" placeholder="Ingrese telefono">
                                </div>
                                <div class="form-group">
                                    <label for="correo_edit">Correo</label>
                                    <input id="correo_edit" type="email" class="form-control" placeholder="Ingrese correo">
                                </div>
                                <div class="form-group">
                                    <label for="adicional_edit">Adicional</label>
                                    <input id="adicional_edit" required type="text" class="form-control" placeholder="Ingrese informacion adicional">
                                </div>
                                <input type="hidden" id="id_cliente">
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
                                Gestion cliente
                                <button type="button" data-toggle="modal" data-target="#crear_cliente" class="btn bg-gradient-primary ml-2">Crear cliente</button>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="adm_catalogo.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Gestion cliente</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section> 
            <section>
                <div class="container-fluid">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Buscar cliente</h3>
                            <div class="input-group">
                                <input id="buscar_cliente" placeholder="Ingrese nombre de cliente" type="text" class="form-control float-left">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="clientes" class="row d-flex align-items-stretch"></div>
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
<script src="../js/cliente.js"></script>