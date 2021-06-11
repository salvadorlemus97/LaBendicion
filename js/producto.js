$(document).ready(function(){
    var edit = false;
    $('.select2').select2();
    rellenar_laboratorios();
    rellenar_tipos()
    rellenar_presentaciones();
    buscar_producto();
    rellenar_proveedores();

    function rellenar_laboratorios() {
        funcion = 'rellenar_laboratorios';

        $.post('../controlador/LaboratorioController.php', {funcion}, (response) => {
            const laboratorios = JSON.parse(response);

            let template = '';
            template += `<option value="0" disabled selected>-- Seleccionar laboratorio --</option>`;
            laboratorios.forEach(laboratorio => {
                template += `
                    <option value="${laboratorio.id}">${laboratorio.nombre}</option>
                `;
            });
            $('#laboratorio').html(template);
        });
    }

    function rellenar_tipos() {
        funcion = 'rellenar_tipos';

        $.post('../controlador/TipoController.php', {funcion}, (response) => {
            const tipos = JSON.parse(response);

            let template = '';
            template += `<option value="0" disabled selected>-- Seleccionar tipo producto --</option>`;
            tipos.forEach(tipo => {
                template += `
                    <option value="${tipo.id}">${tipo.nombre}</option>
                `;
            });
            $('#tipo').html(template);
        });
    }

    function rellenar_presentaciones() {
        funcion = 'rellenar_presentaciones';

        $.post('../controlador/PresentacionController.php', {funcion}, (response) => {
            const presentaciones = JSON.parse(response);

            let template = '';
            template += `<option value="0" disabled selected>-- Seleccionar presentacion producto --</option>`;
            presentaciones.forEach(presentacion => {
                template += `
                    <option value="${presentacion.id}">${presentacion.nombre}</option>
                `;
            });
            $('#presentacion').html(template);
        });
    }

    function rellenar_proveedores() {
        funcion = 'rellenar_proveedores';

        $.post('../controlador/ProveedorController.php', {funcion}, (response) => {
            const proveedores = JSON.parse(response);

            let template = '';
            template += `<option value="0" disabled selected>-- Seleccionar Proveedor --</option>`;
            proveedores.forEach(proveedor => {
                template += `
                    <option value="${proveedor.id}">${proveedor.nombre}</option>
                `;
            });
            $('#proveedor').html(template);
        });
    }

    $('#form-crear-producto').submit(e=>{ 
        let id = $('#id_edit_prod').val();       
        let nombre = $('#nombre_producto').val();
        let concentracion = $('#concentracion').val();
        let adicional = $('#adicional').val();
        let precio = $('#precio').val();
        let laboratorio = $('#laboratorio').val();
        let tipo = $('#tipo').val();
        let presentacion = $('#presentacion').val();

        if (edit == true) {
            funcion = 'editar';
        }else{
            funcion = 'crear';
        }

        $.post('../controlador/ProductoController.php', {id, nombre, concentracion, adicional, precio, laboratorio, tipo, presentacion, funcion}, (response) => {
            
            if (response == 'add') {
                $('#add').hide('slow');
                $('#add').show(2000);
                $('#add').hide(2000);
                $('#form-crear-producto').trigger('reset');
                $('#tipo').val('0').trigger('change');
                $('#laboratorio').val('0').trigger('change');
                $('#presentacion').val('0').trigger('change');
                buscar_producto();
            }
            if (response == 'edit') {
                $('#edit_prod').hide('slow');
                $('#edit_prod').show(2000);
                $('#edit_prod').hide(2000);
                $('#form-crear-producto').trigger('reset');
                $('#tipo').val('0').trigger('change');
                $('#laboratorio').val('0').trigger('change');
                $('#presentacion').val('0').trigger('change');
                buscar_producto();
            }
            if (response == 'noedit'){
                $('#noadd').hide('slow');
                $('#noadd').show(2000);
                $('#noadd').hide(2000);
                $('#form-crear-producto').trigger('reset');
            }  
            if (response == 'noadd'){
                $('#noadd').hide('slow');
                $('#noadd').show(2000);
                $('#noadd').hide(2000);
                $('#form-crear-producto').trigger('reset');
            }  
            edit = false;      
        });
        e.preventDefault();
    });

    function buscar_producto(consulta) {
        funcion = 'buscar';
        $.post('../controlador/ProductoController.php', {consulta, funcion}, (response) => {
            const productos = JSON.parse(response);

            let template = '';
            productos.forEach(producto => {
                template += `
                    <div prodavatar="${producto.avatar}" prodpresentacion="${producto.presentacion_id}" prodtipo="${producto.tipo_id}" prodlaboratorio="${producto.laboratorio_id}" prodadicional="${producto.adicional}" prodconcentracion="${producto.concentracion}" prodprecio="${producto.precio}" prodid="${producto.id}" prodstock="${producto.stock}" prodnombre="${producto.nombre}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                        <div class="card bg-light">
                            <div class="card-header text-muted border-bottom-0">
                                <i class="fas fa-lg fa-cubes mr-2"></i>${producto.stock}
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="lead"><b>${producto.nombre}</b></h2>
                                        <h4 class="lead"><b><i class="fas fa-lg fa-dollar-sign mr-2"></i> ${producto.precio}</b></h4>
                                        
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-mortar-pestle"></i></span> Concentracion: ${producto.concentracion}</li>
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-prescription-bottle-alt"></i></span> Adicional: ${producto.adicional}</li>
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-flask"></i></span> Laboratorio: ${producto.laboratorio}</li>
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-copyright"></i></span> Tipo: ${producto.tipo}</li>
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-pills"></i></span> Presentacion: ${producto.presentacion}</li>
                                        </ul>
                                    </div>
                                    <div class="col-5 text-center">
                                        <img src="${producto.avatar}" alt="" class="img-circle img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <button class="avatar btn btn-sm bg-teal" type="button" data-toggle="modal" data-target="#cambio_logo">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button class="editar btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#crear_producto">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="lote btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#crear_lote">
                                        <i class="fas fa-plus-square"></i>
                                    </button>
                                    <button class="borrar btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#productos').html(template);
        });
    }

    $(document).on('keyup', '#buscar-producto', function(){
        let valor = $(this).val();
        if (valor != '') {
            buscar_producto(valor)
        }else{
            buscar_producto()
        }
    });

    $(document).on('click', '.avatar', (e) => {
        funcion = 'cambiar_avatar';
        const elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id = $(elemento).attr('prodid');
        const avatar = $(elemento).attr('prodavatar');
        const nombre = $(elemento).attr('prodnombre');

        $('#funcion').val(funcion);
        $('#id_logo_prod').val(id);
        $('#avatar').val(avatar);
        $('#nombre_logo').html(nombre);
        $('#logoactual').attr('src', avatar);        
    });

    $('#form-logo').submit(e => {
        let formData = new FormData($('#form-logo')[0]);
        $.ajax({
            url: '../controlador/ProductoController.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(response){
            const json = JSON.parse(response);
            if (json.alert == 'edit') {
                $('#logoactual').attr('src', json.ruta);
                $('#edit').hide('slow');
                $('#edit').show(2000);
                $('#edit').hide(2000);
                $('#form-logo').trigger('reset');
                buscar_producto();
            }else{
                $('#noedit').hide('slow');
                $('#noedit').show(2000);
                $('#noedit').hide(2000);
                $('#form-logo').trigger('reset');
            }            
        });
        e.preventDefault();
    });

    $(document).on('click', '.editar', (e) => {
        const elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id = $(elemento).attr('prodid');
        const nombre = $(elemento).attr('prodnombre');
        const concentracion = $(elemento).attr('prodconcentracion');
        const adicional = $(elemento).attr('prodadicional');
        const precio = $(elemento).attr('prodprecio');
        const laboratorio = $(elemento).attr('prodlaboratorio');
        const tipo = $(elemento).attr('prodtipo');
        const presentacion = $(elemento).attr('prodpresentacion');

        $('#id_edit_prod').val(id);
        $('#nombre_producto').val(nombre);    
        $('#concentracion').val(concentracion);
        $('#adicional').val(adicional);
        $('#precio').val(precio);
        $('#precio').val(precio);
        $('#laboratorio').val(laboratorio).trigger('change');
        $('#tipo').val(tipo).trigger('change');
        $('#presentacion').val(presentacion).trigger('change');
        edit = true;
    });

    $(document).on('click', '.borrar', function(e){
        funcion = 'borrar';
        const elemento = $(this)[0].parentElement.parentElement.parentElement.parentElement;
        let id = $(elemento).attr('prodid');
        let nombre = $(elemento).attr('prodnombre');
        let avatar = $(elemento).attr('prodavatar');
        
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger mr-1'
            },
            buttonsStyling: false
          })
          
          swalWithBootstrapButtons.fire({
            title: 'Quiere eliminar '+nombre+'?',
            text: "No podra revertir esta accion!",
            imageUrl: ''+avatar+'',
            imageWidth: 100,
            imageHeight:100,
            showCancelButton: true,
            confirmButtonText: 'Si, Eliminar registro!',
            cancelButtonText: 'No, Cancelar accion!',
            reverseButtons: true
          }).then((result) => {
            if (result.value) {
                $.post('../controlador/ProductoController.php', {id, funcion}, (response) => {
                    edit = false;
                    if (response == 'borrado') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'El producto '+nombre+' fue borrado.',
                            'success'
                        )
                        buscar_producto();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'No se pudo borrar!',
                            'El producto '+nombre+' no fue borrado porque tiene stock disponible.',
                            'error'
                        )
                    }
                });
              
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'El producto '+nombre+' no fue borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.lote', (e) => {
        const elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id = $(elemento).attr('prodid');
        const nombre = $(elemento).attr('prodnombre');

        $('#id_lote_prod').val(id);
        $('#nombre_producto_lote').html(nombre);       
    });

    $('#form-crear-lote').submit(e => {
        let id_producto = $('#id_lote_prod').val();
        let proveedor = $('#proveedor').val();
        let stock = $('#stock').val();
        let vencimiento = $('#vencimiento').val();
        funcion = 'crear';
        $.post('../controlador/LoteController.php', {funcion, vencimiento, stock, proveedor, id_producto}, (response) => {
            if (response == 'add') {
                $('#add-lote').hide('slow');
                $('#add-lote').show(2000);
                $('#add-lote').hide(2000);
                $('#form-crear-lote').trigger('reset');
                $('#proveedor').val('0').trigger('change');
                buscar_producto();
            }
        });
        e.preventDefault();
    });

    $('#btn_reporte').on('click', (e) => {
        mostrar_loader('generar_reporte_pdf');
        funcion = 'reporte_producto';
        $.post('../controlador/ProductoController.php', {funcion}, (response) => {
            if (response == "") {
                cerrar_loader('exito_reporte');
                window.open('../pdf/pdf-'+funcion+'.pdf', '_blank');
            }else{
                cerrar_loader('error_reporte');
            }            
        });
    });

    function mostrar_loader(mensaje){
        var texto = null;
        var mostrar = false;
        switch (mensaje) {
            case 'generar_reporte_pdf':
                texto = 'Se esta generando el reporte en formato PDF, por favor espere...';
                mostrar = true;
            break;
        }

        if (mostrar) {
            Swal.fire({
                title: texto,
                imageUrl: '../img/cargando.gif',
                imageWidth: 150,
                imageHeight: 150,
                imageAlt: 'Custom image',
                showConfirmButton: false,
              })
        }
    }

    function cerrar_loader(mensaje){
        var tipo  =  null;
        var texto = null;
        var mostrar = false;
        switch (mensaje) {
            case 'exito_reporte':
                tipo = 'success';
                texto = 'El reporte fue generado correctamente';
                mostrar = true;
            break;
            case 'error_reporte':
                tipo = 'error';
                texto = 'El reporte no pudo generarse.';
                mostrar = true;
            break;
            default:
                swal.close();
                break;
        }

        if (mostrar) {
            Swal.fire({
                position: 'center',
                icon: tipo,
                text: texto,
                showConfirmButton: true
            });
        }
    }
});