$(document).ready(function(){
	$('#id_cliente').attr('disabled', true);
	buscar_cliente();
	var funcion;

	function buscar_cliente(consulta){
        funcion = 'buscar';
        $.post('../controlador/ClienteController.php', {funcion, consulta}, (response) => {
        	
            const clientes = JSON.parse(response);
            let template = '';

            clientes.forEach(cliente => {
                template += `
                <div clieid="${cliente.id}" clieavatar="${cliente.avatar}" clienombre="${cliente.nombre}" clietelefono="${cliente.telefono}" cliecorreo="${cliente.correo}" clieadicional="${cliente.adicional}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                <div class="card bg-light">
                  <div class="card-header text-muted border-bottom-0">
                    <h1 class="badge badge-success">Cliente</h1>
                  </div>
                  <div class="card-body pt-0">
                    <div class="row">
                      <div class="col-7">
                        <h2 class="lead"><b>${cliente.nombre}</b></h2>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> DUI: ${cliente.dui}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Edad #: ${cliente.edad}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Telefono: ${cliente.telefono}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Correo: ${cliente.correo}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Sexo: ${cliente.sexo}</li>
                          <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Adicional: ${cliente.adicional}</li>
                        </ul>
                      </div>
                      <div class="col-5 text-center">
                        <img src="${cliente.avatar}" alt="" class="img-circle img-fluid">
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="text-right">
                      <button type="button" data-toggle="modal" data-target="#editar_cliente" class="btn btn-sm btn-success editar" title="Editar Cliente">
                        <i class="fas fa-pencil-alt"></i>
                      </button>
                      <button class="btn btn-sm btn-danger borrar" title="Borrar cliente">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
                `;
            });
            $('#clientes').html(template);

            
        });
    }

    $(document).on('keyup', '#buscar_cliente', function(){
        let valor = $(this).val();
        if (valor != '') {
            buscar_cliente(valor);
        }else{
            buscar_cliente();
        }
    });

    $('#form-crear').submit(e => {
        let nombre = $('#nombre').val();
        let apellido = $('#apellido').val();
        let dui = $('#dui').val();
        let nacimiento = $('#nacimiento').val();
        let telefono = $('#telefono').val();
        let correo = $('#correo').val();
        let sexo = $('#sexo').val();
        let adicional = $('#adicional').val();
        funcion = 'crear';
        $.post('../controlador/ClienteController.php', {funcion, nombre, apellido, dui, nacimiento, telefono, correo, sexo, adicional}, (response) => {
        	if (response == 'add') {
                $('#add-clie').hide('slow');
                $('#add-clie').show(2000);
                $('#add-clie').hide(2000);
                $('#form-crear').trigger('reset');
                buscar_cliente();
            }
            if (response == 'noadd') {
                $('#noadd-clie').hide('slow');
                $('#noadd-clie').show(2000);
                $('#noadd-clie').hide(2000);
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '.editar', (e) => {
    	let elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
    	let correo = $(elemento).attr('cliecorreo');
    	let telefono = $(elemento).attr('clietelefono');
    	let adicional = $(elemento).attr('clieadicional');
    	let id = $(elemento).attr('clieid');

    	$('#telefono_edit').val(telefono);
    	$('#correo_edit').val(correo);
    	$('#adicional_edit').val(adicional);
    	$('#id_cliente').val(id);
    });

    $('#form-editar').submit(e => {
        let telefono = $('#telefono_edit').val();
        let correo = $('#correo_edit').val();
        let id = $('#id_cliente').val();
        let adicional = $('#adicional_edit').val();
        funcion = 'editar';
        $.post('../controlador/ClienteController.php', {funcion, telefono, correo, id, adicional}, (response) => {
        	console.log(response);
        	if (response == 'edit') {
                $('#edit-clie').hide('slow');
                $('#edit-clie').show(2000);
                $('#edit-clie').hide(2000);
                $('#form-editar').trigger('reset');
                buscar_cliente();
            }
            if (response == 'noedit') {
                $('#noedit-clie').hide('slow');
                $('#noedit-clie').show(2000);
                $('#noedit-clie').hide(2000);
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '.borrar', function(e){
        funcion = 'borrar';
        let elemento = $(this)[0].parentElement.parentElement.parentElement.parentElement;
        let id = $(elemento).attr('clieid');
        let nombre = $(elemento).attr('clienombre');
        let avatar = $(elemento).attr('clieavatar');
        
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
                $.post('../controlador/ClienteController.php', {id, funcion}, (response) => {
                    
                    if (response == 'borrado') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'El cliente '+nombre+' fue borrado.',
                            'success'
                        )
                        buscar_cliente();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'No se pudo borrar!',
                            'El cliente '+nombre+' no fue borrado porque esta siendo utilizado en un lote.',
                            'error'
                        )
                    }
                });
              
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'El cliente '+nombre+' no fue borrado',
                'error'
              )
            }
          })
    });
});