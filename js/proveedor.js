$(document).ready(function(){
    var funcion;
    var edit = false;
    buscar_prov();

    $('#form-crear').submit(e => {
        let id = $('#id_edit_prov').val();
        let nombre = $('#nombre').val();
        let telefono = $('#telefono').val();
        let correo = $('#correo').val();
        let direccion = $('#direccion').val();
        if (edit == true) {
          funcion = 'editar';
        }else{
          funcion = 'crear';
        }
        $.post('../controlador/ProveedorController.php', {id, funcion, nombre, telefono, correo, direccion}, (response) => {
            if (response == 'add') {
                $('#add-prov').hide('slow');
                $('#add-prov').show(2000);
                $('#add-prov').hide(2000);
                $('#form-crear').trigger('reset');
                buscar_prov();
            }
            if (response == 'noadd' || response == 'noedit') {
                $('#noadd-prov').hide('slow');
                $('#noadd-prov').show(2000);
                $('#noadd-prov').hide(2000);
                $('#form-crear').trigger('reset');
            }

            if (response == 'edit') {
              $('#edit-prove').hide('slow');
              $('#edit-prove').show(2000);
              $('#edit-prove').hide(2000);
              $('#form-crear').trigger('reset');
              buscar_prov();
            }
            edit = false;
        });
        e.preventDefault();
    });

    function buscar_prov(consulta){
        funcion = 'buscar';
        $.post('../controlador/ProveedorController.php', {funcion, consulta}, (response) => {
            const proveedores = JSON.parse(response);
            let template = '';

            proveedores.forEach(proveedor => {
                template += `
                <div provavatar="${proveedor.avatar}" provdireccion="${proveedor.direccion}" provcorreo="${proveedor.correo}" provtelefono="${proveedor.telefono}" provnombre="${proveedor.nombre}" provid="${proveedor.id}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                <div class="card bg-light">
                  <div class="card-header text-muted border-bottom-0">
                    <h1 class="badge badge-success">Proveedor</h1>
                  </div>
                  <div class="card-body pt-0">
                    <div class="row">
                      <div class="col-7">
                        <h2 class="lead"><b>${proveedor.nombre}</b></h2>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Direccion: ${proveedor.direccion}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Telefono #: ${proveedor.telefono}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Correo: ${proveedor.correo}</li>
                        </ul>
                      </div>
                      <div class="col-5 text-center">
                        <img src="${proveedor.avatar}" alt="" class="img-circle img-fluid">
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="text-right">
                      <button type="button" data-toggle="modal" data-target="#cambio_logo" class="btn btn-sm btn-info avatar" title="Editar logo">
                        <i class="fas fa-image"></i>
                      </button>
                      <button type="button" data-toggle="modal" data-target="#crear_proveedor" class="btn btn-sm btn-success editar" title="Editar Proveedor">
                        <i class="fas fa-pencil-alt"></i>
                      </button>
                      <button class="btn btn-sm btn-danger borrar" title="Borrar proveedor">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
                `;
            });
            $('#proveedores').html(template);
        });
    }

    $(document).on('keyup', '#buscar_proveedor', function(){
        let valor = $(this).val();
        if (valor != '') {
            buscar_prov(valor);
        }else{
            buscar_prov();
        }
    });

    $(document).on('click', '.avatar', (e) => {
        funcion = 'cambiar_logo';
        const elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id = $(elemento).attr('provid');
        const nombre = $(elemento).attr('provnombre');
        const avatar = $(elemento).attr('provavatar');

        $('#logoactual').attr('src', avatar);
        $('#nombre_logo').html(nombre);
        $('#id_logo_prov').val(id);
        $('#funcion').val(funcion);
        $('#avatar').val(avatar);
    });

    $('#form-logo').submit(e => {
        let formData = new FormData($('#form-logo')[0]);
        $.ajax({
            url: '../controlador/ProveedorController.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(response){
            const json = JSON.parse(response);
            if (json.alert == 'edit') {
                $('#logoactual').attr('src', json.ruta);
                $('#edit-prov').hide('slow');
                $('#edit-prov').show(2000);
                $('#edit-prov').hide(2000);
                $('#form-logo').trigger('reset');
                buscar_prov();
            }else{
                $('#noedit-prov').hide('slow');
                $('#noedit-prov').show(2000);
                $('#noedit-prov').hide(2000);
                $('#form-logo').trigger('reset');
            }            
        });
        e.preventDefault();
    });

    $(document).on('click', '.borrar', function(e){
        funcion = 'borrar';
        const elemento = $(this)[0].parentElement.parentElement.parentElement.parentElement;
        let id = $(elemento).attr('provid');
        let nombre = $(elemento).attr('provnombre');
        let avatar = $(elemento).attr('provavatar');
        
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
                $.post('../controlador/ProveedorController.php', {id, funcion}, (response) => {
                    
                    if (response == 'borrado') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'El proveedor '+nombre+' fue borrado.',
                            'success'
                        )
                        buscar_prov();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'No se pudo borrar!',
                            'El proveedor '+nombre+' no fue borrado porque esta siendo utilizado en un lote.',
                            'error'
                        )
                    }
                });
              
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'El proveedor '+nombre+' no fue borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar', (e) => {
      const elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
      const id = $(elemento).attr('provid');
      const nombre = $(elemento).attr('provnombre');
      const direccion = $(elemento).attr('provdireccion');
      const telefono = $(elemento).attr('provtelefono');
      const correo = $(elemento).attr('provcorreo');

      $('#id_edit_prov').val(id);
      $('#nombre').val(nombre);
      $('#direccion').val(direccion);
      $('#telefono').val(telefono);
      $('#correo').val(correo);
      edit = true;
  });
});