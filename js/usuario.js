$(document).ready(function(){
    var id_usuario = $('#id_usuario').val();
    var funcion = '';
    var edit = false;
    buscar_usuario(id_usuario);

    function buscar_usuario(dato) {
        funcion = 'buscar_usuario';
        $.post('../controlador/UsuarioController.php', {dato, funcion}, function(response){ //(response)=>{}
            let nombre = '';
            let apellidos = '';
            let edad = '';
            let dui = '';
            let tipo = '';
            let telefono = '';
            let residencia = '';
            let correo = '';
            let sexo = '';
            let adicional = '';
            let avatar = '';
            const usuario = JSON.parse(response);

            nombre += `${usuario.nombre}`;
            apellidos += `${usuario.apellidos}`;
            edad += `${usuario.edad}`;
            dui += `${usuario.dui}`;
            if (usuario.tipo == 'Root') {
                tipo += `
                  <h1 class="badge badge-danger">${usuario.tipo}</h1>
                `;
              }

              if (usuario.tipo == 'Tecnico') {
                tipo += `
                  <h1 class="badge badge-info">${usuario.tipo}</h1>
                `;
              }

              if (usuario.tipo == 'Administrador') {
                tipo += `
                  <h1 class="badge badge-warning">${usuario.tipo}</h1>
                `;
              }
            telefono += `${usuario.telefono}`;
            residencia += `${usuario.residencia}`;
            correo += `${usuario.correo}`;
            sexo += `${usuario.sexo}`;
            adicional += `${usuario.adicional}`;
            avatar += `${usuario.avatar}`;

            $('#nombre_us').html(nombre);
            $('#apellidos_us').html(apellidos);
            $('#edad').html(edad);
            $('#dui_us').html(dui);
            $('#us_tipo').html(tipo);
            $('#telefono_us').html(telefono);
            $('#residencia_us').html(residencia);
            $('#correo_us').html(correo);
            $('#sexo_us').html(sexo);
            $('#adicional_us').html(adicional);
            $('#avatar2').attr('src', avatar);
            $('#avatar1').attr('src', avatar);
            $('#avatar3').attr('src', avatar);
            $('#avatar4').attr('src', avatar);
        });
    }

    $(document).on('click', '.edit', function(e){
        funcion = 'capturar_datos';
        edit = true;
        $.post('../controlador/UsuarioController.php', {funcion, id_usuario}, (response) =>  {
            const usuario = JSON.parse(response);
            $('#telefono').val(usuario.telefono);
            $('#residencia').val(usuario.residencia);
            $('#correo').val(usuario.correo);
            $('#sexo').val(usuario.sexo);
            $('#adicional').val(usuario.adicional);
        });
    });

    $('#form-usuario').submit(e => {
        if (edit == true) {
            let telefono = $('#telefono').val();
            let residencia = $('#residencia').val();
            let correo = $('#correo').val();
            let sexo = $('#sexo').val();
            let adicional = $('#adicional').val();

            funcion = 'editar_usuario';
            $.post('../controlador/UsuarioController.php', {id_usuario, funcion, telefono, residencia, correo, sexo, adicional}, function(response){
                if (response=='editado') {
                    $('#editado').hide('slow');
                    $('#editado').show(2000);
                    $('#editado').hide(2000);
                    $('#form-usuario').trigger('reset');
                }
                edit = false;
                buscar_usuario(id_usuario);
            });
        }else{
            $('#noeditado').hide('slow');
            $('#noeditado').show(2000);
            $('#noeditado').hide(2000);
            $('#form-usuario').trigger('reset');
        }
        e.preventDefault();
    });

    $('#form-pass').submit(function(e){
        let oldpass = $('#oldpass').val();
        let newpass = $('#newpass').val();

        funcion = 'cambiar_contra';
        $.post('../controlador/UsuarioController.php', {id_usuario, funcion, oldpass, newpass}, (response)=>{
            if (response == 'update') {
                $('#update').hide('slow');
                $('#update').show(2000);
                $('#update').hide(2000);
                $('#form-pass').trigger('reset');
            }else{
                $('#noupdate').hide('slow');
                $('#noupdate').show(2000);
                $('#noupdate').hide(2000);
                $('#form-pass').trigger('reset');
            }
        });
        e.preventDefault();
    });

    $('#form-foto').submit(e => {
        let form_data = new FormData($('#form-foto')[0]);
        $.ajax({
            url: '../controlador/UsuarioController.php',
            type: 'POST',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(response){
            const json = JSON.parse(response);
            if (json.alert == 'edit') {
                $('#avatar1').attr('src', json.ruta);
                $('#edit').hide('slow');
                $('#edit').show(2000);
                $('#edit').hide(2000);
                $('#form-foto').trigger('reset');
                buscar_usuario(id_usuario);
            }else{
                $('#noedit').hide('slow');
                $('#noedit').show(2000);
                $('#noedit').hide(2000);
                $('#form-foto').trigger('reset');
            }         
        });
        e.preventDefault();
    });
});