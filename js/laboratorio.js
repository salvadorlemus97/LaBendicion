$(document).ready(function(){
    var funcion;
    var edit = false;
    $('#id_editar_lab').attr('disabled', true);
    buscar_lab();

    $('#form-crear-laboratorio').submit(e => {
        let nombre_laboratorio = $('#nombre-laboratorio').val();
        let id_editado = $('#id_editar_lab').val();
        if (edit == false) {
            funcion = 'crear';
        }else{
            funcion = 'editar';
        }
        
        $.post('../controlador/LaboratorioController.php', {nombre_laboratorio, funcion, id_editado}, function(response){
            if (response == 'add') {
                $('#add-laboratorio').hide('slow');
                $('#add-laboratorio').show(2000);
                $('#add-laboratorio').hide(2000);
                $('#form-crear-laboratorio').trigger('reset');
                buscar_lab();
            }
            if(response == 'noadd'){
                $('#noadd-laboratorio').hide('slow');
                $('#noadd-laboratorio').show(2000);
                $('#noadd-laboratorio').hide(2000);
                $('#form-crear-laboratorio').trigger('reset');
            }

            if (response == 'edit') {
                $('#edit-lab').hide('slow');
                $('#edit-lab').show(2000);
                $('#edit-lab').hide(2000);
                $('#form-crear-laboratorio').trigger('reset');
                buscar_lab();
            }
            edit = false;
        });
        e.preventDefault();
    });

    $(document).on('keyup', '#buscar-laboratorio', function(){
        let valor = $(this).val();
        if (valor != '') {
            buscar_lab(valor);
        }else{
            buscar_lab();
        }
    });

    function buscar_lab(consulta){
        funcion = 'buscar';
        $.post('../controlador/LaboratorioController.php', {funcion, consulta}, function(response){
            const laboratorios = JSON.parse(response);
            let template = '';

            laboratorios.forEach(laboratorio => {
                template += `
                    <tr labid="${laboratorio.id}" labnombre="${laboratorio.nombre}" labavatar="${laboratorio.avatar}">
                        <td>${laboratorio.nombre}</td>
                        <td>
                            <img src="${laboratorio.avatar}" class="img-fluid rounded" width="40" heigth="40">
                        </td>
                        <td>
                            <button class="avatar btn btn-info btn-sm" title="Cambiar logo de laboratorio" type="button" data-toggle="modal" data-target="#cambio_logo">
                                <i class="fas fa-image"></i>
                            </button>
                            <button class="editar btn btn-success btn-sm" title="Editar laboratorio" type="button" data-toggle="modal" data-target="#crearlaboratorio">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="borrar btn btn-danger btn-sm" title="Borrar laboratorio">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#laboratorios').html(template);
        });
    }

    $(document).on('click', '.avatar', function(e){
        funcion = 'cambiar_logo';
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('labid');
        let nombre = $(elemento).attr('labnombre');
        let avatar = $(elemento).attr('labavatar');

        $('#logoactual').attr('src',avatar);
        $('#nombre_logo').html(nombre);
        $('#funcion').val(funcion);
        $('#id_logo_lab').val(id);
    });

    $('#form-logo').submit(e => {
        let form_data = new FormData($('#form-logo')[0]);
        $.ajax({
            url: '../controlador/LaboratorioController.php',
            type: 'POST',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(response){
            const json = JSON.parse(response);
            if (json.alert == 'edit') {
                $('#logoactual').attr('src',json.ruta);
                $('#form-logo').trigger('reset');
                $('#edit').hide('slow');
                $('#edit').show(2000);
                $('#edit').hide(2000);
                buscar_lab();
            }else{
                $('#noedit').hide('slow');
                $('#noedit').show(2000);
                $('#noedit').hide(2000);
                $('#form-logo').trigger('reset');
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '.borrar', function(e){
        funcion = 'borrar';
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('labid');
        let nombre = $(elemento).attr('labnombre');
        let avatar = $(elemento).attr('labavatar');

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
                $.post('../controlador/LaboratorioController.php', {id, funcion}, (response) => {
                    edit = false;
                    if (response == 'borrado') {
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'El laboratorio '+nombre+' fue borrado.',
                            'success'
                        )
                        buscar_lab();
                    }else{
                        swalWithBootstrapButtons.fire(
                            'No se pudo borrar!',
                            'El laboratorio '+nombre+' no fue borrado porque esta siendo utilizado en un producto.',
                            'error'
                        )
                    }
                });
              
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'El laboratorio '+nombre+' no fue borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar', function(e){
        const elemento = $(this)[0].parentElement.parentElement;
        let id = $(elemento).attr('labid');
        let nombre = $(elemento).attr('labnombre');

        $('#id_editar_lab').val(id);
        $('#nombre-laboratorio').val(nombre);
        edit = true;
    });
});