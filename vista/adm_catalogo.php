<?php
    session_start();
    if ($_SESSION['us_tipo'] == 1 || $_SESSION['us_tipo'] == 3 || $_SESSION['us_tipo'] == 2) {
        include_once 'layouts/header.php';
    ?>        
        <title>Adm | Catálogo</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php include_once 'layouts/nav.php'; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="animate__animated animate__shakeY">Catálogo</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Catálogo</li>
                        </ol>
                    </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->

            <section>
                <div class="container-fluid">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Lotes en riesgo</h3>                            
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="animate__animated  animate__fadeIn table table-hover text-nowrap">
                                <thead class="table-success">
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Stock</th>
                                        <th>Laboratorio</th>
                                        <th>Presentacion</th>
                                        <th>Proveedor</th>
                                        <th>Mes</th>
                                        <th>Dia</th>
                                    </tr>
                                </thead>
                                <tbody id="lotes" class="table-active"></tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
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
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper --> 
    <?php
        include_once 'layouts/footer.php';
    }else{
        header('Location: ../index.php');
    }
?>
<script src="../js/catalogo.js"></script>
<script src="../js/carrito.js"></script>