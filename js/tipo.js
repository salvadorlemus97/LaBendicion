$(document).ready(function(){
    var funcion;
    var edit = false;
    $('#id_editar_tipo').attr('disabled', true);
    buscar_tipo();

    $('#form-crear-tipo').submit(e => {
        let nombre_tipo = $('#nombre-tipo').val();
        let id_editado = $('#id_editar_tipo').val();
        if (edit == false) {
            funcion = 'crear';
        }else{
            funcion = 'editar';
        }
        
        $.post('../controlador/TipoController.php', {nombre_tipo, funcion, id_editado}, function(response){
            if (response == 'add') {
                $('#add-tipo').hide('slow');
                $('#add-tipo').show(2000);
                $('#add-tipo').hide(2000);
                $('#form-crear-tipo').trigger('reset');
                buscar_tipo();
            }
            if(response == 'noadd'){
                $('#noadd-tipo').hide('slow');
                $('#noadd-tipo').show(2000);
                $('#noadd-tipo').hide(2000);
                $('#form-crear-tipo').trigger('reset');
            }

            if (response == 'edit') {
                $('#edit-tipo').hide('slow');
                $('#edit-tipo').show(2000);
                $('#edit-tipo').hide(2000);
                $('#form-crear-tipo').trigger('reset');
                buscar_tipo();
            }
            edit = false;
        });
        e.preventDefault();
    });

    $(document).on('keyup', '#buscar-tipo', function(){
        let valor = $(this).val();
        if (valor != '') {
            buscar_tipo(valor);
        }else{
            buscar_tipo();
        }
    });

    function buscar_tipo(consulta){
        funcion = 'buscar';
        $.post('../controlador/TipoController.php', {funcion, consulta}, function(response){
            const tipos = JSON.parse(response);
            let template = '';

            tipos.forEach(tipo => {
                template += `
                    <tr tipoid="${tipo.id}" tiponombre="${tipo.nombre}">
                        <td>${tipo.nombre}</td>
                        <td>
                            <button class="editar-tipo btn btn-success btn-sm" title="Editar tipo" type="button" data-toggle="modal" data-target="#creartipo">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="borrar-tipo btn btn-danger btn-sm" title="Borrar tipo">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#tipos').html(template);
        });
    }

    $(document).on('click', '.borrar-tipo', function(e){
        funcion = 'borrar';
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('tipoid');
        let nombre = $(elemento).attr('tiponombre');

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
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, Eliminar registro!',
            cancelButtonText: 'No, Cancelar accion!',
            reverseButtons: true
          }).then((result) => {
            if (result.value) {
                $.post('../controlador/TipoController.php', {id, funcion}, (response) => {
                    edit = false;
                    if (response == 'borrado') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'El tipo '+nombre+' fue borrado.',
                            'success'
                        )
                        buscar_tipo();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'No se pudo borrar!',
                            'El tipo '+nombre+' no fue borrado porque esta siendo utilizado en un producto.',
                            'error'
                        )
                    }
                });
              
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'El tipo '+nombre+' no fue borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar-tipo', function(e){
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('tipoid');
        let nombre = $(elemento).attr('tiponombre');

        $('#id_editar_tipo').val(id);
        $('#nombre-tipo').val(nombre);
        edit = true;
    });
});