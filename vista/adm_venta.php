<?php
    session_start();
    if ($_SESSION['us_tipo'] == 3 || $_SESSION['us_tipo'] == 1 || $_SESSION['us_tipo'] == 2) {
        include_once 'layouts/header.php';
    ?>        
        <title>Adm | Gestion Ventas</title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php include_once 'layouts/nav.php'; ?>   

        <!-- Modal -->
        <div class="modal fade" id="vista_venta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="card-success">
                        <div class="card-header">
                            <h3 class="card-title">Registros de venta</h3>
                            <button class="close" data-dismiss="modal" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="codigo_venta">Codigo venta: </label>
                                <span id="codigo_venta"></span>
                            </div>
                            <div class="form-group">
                                <label for="fecha">Fecha: </label>
                                <span id="fecha"></span>
                            </div>
                            <div class="form-group">
                                <label for="cliente">Cliente: </label>
                                <span id="cliente"></span>
                            </div>
                            <div class="form-group">
                                <label for="dui">DUI: </label>
                                <span id="dui"></span>
                            </div>
                            <div class="form-group">
                                <label for="vendedor">Vendedor: </label>
                                <span id="vendedor"></span>
                            </div>
                            <table class="table table-hover text-nowrap">
                                <thead class="table-success">
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Producto</th>
                                        <th>Concentracion</th>
                                        <th>Adicional</th>
                                        <th>Laboratorio</th>
                                        <th>Presentacion</th>
                                        <th>Tipo</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="registros" class="table-warning"></tbody>
                            </table>
                            <div class="float-right input-group-append">
                                <h3 class="m-2">Total: $</h3>
                                <h3 class="m-2" id="total"></h3>
                            </div>
                        </div>  
                        <div class="card-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
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
                                Gestion ventas
                                
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="adm_catalogo.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Gestion ventas</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section> 
            <section>
                <div class="container-fluid">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Consultas</h3>
                           
                        </div>
                        <div class="card-body">
                            <div class="row">
                              <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                  <div class="inner">
                                    <h3 id="venta_dia_vendedor">0</h3>

                                    <p>Venta del dia por vendedor</p>
                                  </div>
                                  <div class="icon">
                                    <i class="fas fa-user"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                  <div class="inner">
                                    <h3 id="venta_diaria">0</h3>
                                    <!-- sumatoria de cada uno de los vendedores que esta evndiendo en el dia -->
                                    <p>Venta diaria</p>
                                  </div>
                                  <div class="icon">
                                    <i class="fas fa-shopping-bag"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                  <div class="inner">
                                    <h3 id="venta_mensual">0</h3>

                                    <p>Venta mensual</p>
                                  </div>
                                  <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                  <div class="inner">
                                    <h3 id="venta_anual">0</h3>

                                    <p>Venta anual</p>
                                  </div>
                                  <div class="icon">
                                    <i class="fas fa-signal"></i>
                                  </div>
                                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                              </div>
                              <!-- ./col -->
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </section> 
            <section>
                <div class="container-fluid">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Buscar ventas</h3>
                           
                        </div>
                        <div class="card-body">
                            <table id="tabla_venta" class="table table-hover text-nowrap display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>DUI</th>
                                        <th>Total</th>
                                        <th>Vendedor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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
<script src="../js/datatables.js"></script>
<script src="../js/venta.js"></script>