$(document).ready(function(){
    var funcion;
    var edit = false;
    $('#id_editar_pre').attr('disabled', true);
    buscar_pre();

    $('#form-crear-presentacion').submit(e => {
        let nombre_presentacion = $('#nombre-presentacion').val();
        let id_editado = $('#id_editar_pre').val();
        if (edit == false) {
            funcion = 'crear';
        }else{
            funcion = 'editar';
        }
        
        $.post('../controlador/PresentacionController.php', {nombre_presentacion, funcion, id_editado}, function(response){
            if (response == 'add') {
                $('#add-pre').hide('slow');
                $('#add-pre').show(2000);
                $('#add-pre').hide(2000);
                $('#form-crear-presentacion').trigger('reset');
                buscar_pre();
            }
            if(response == 'noadd'){
                $('#noadd-pre').hide('slow');
                $('#noadd-pre').show(2000);
                $('#noadd-pre').hide(2000);
                $('#form-crear-presentacion').trigger('reset');
            }

            if (response == 'edit') {
                $('#edit-pre').hide('slow');
                $('#edit-pre').show(2000);
                $('#edit-pre').hide(2000);
                $('#form-crear-presentacion').trigger('reset');
                buscar_pre();
            }
            edit = false;
        });
        e.preventDefault();
    });

    $(document).on('keyup', '#buscar-presentacion', function(){
        let valor = $(this).val();
        if (valor != '') {
            buscar_pre(valor);
        }else{
            buscar_pre();
        }
    });

    function buscar_pre(consulta){
        funcion = 'buscar';
        $.post('../controlador/PresentacionController.php', {funcion, consulta}, function(response){
            const presentaciones = JSON.parse(response);
            let template = '';

            presentaciones.forEach(presentacion => {
                template += `
                    <tr preid="${presentacion.id}" prenombre="${presentacion.nombre}">
                        <td>${presentacion.nombre}</td>
                        <td>
                            <button class="editar-pre btn btn-success btn-sm" title="Editar presentacion" type="button" data-toggle="modal" data-target="#crearpresentacion">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="borrar-pre btn btn-danger btn-sm" title="Borrar presentacion">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#presentaciones').html(template);
        });
    }

    $(document).on('click', '.borrar-pre', function(e){
        funcion = 'borrar';
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('preid');
        let nombre = $(elemento).attr('prenombre');

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
                $.post('../controlador/PresentacionController.php', {id, funcion}, (response) => {
                    edit = false;
                    if (response == 'borrado') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'La presentacion '+nombre+' fue borrado.',
                            'success'
                        )
                        buscar_pre();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'No se pudo borrar!',
                            'La presentacion '+nombre+' no fue borrado porque esta siendo utilizado en un producto.',
                            'error'
                        )
                    }
                });
              
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'La presentacion '+nombre+' no fue borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar-pre', function(e){
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('preid');
        let nombre = $(elemento).attr('prenombre');

        $('#id_editar_pre').val(id);
        $('#nombre-presentacion').val(nombre);
        edit = true;
    });
});