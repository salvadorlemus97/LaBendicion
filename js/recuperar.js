$(document).ready(function(){
    $('#aviso1').hide();
    $('#aviso').hide();

    $('#form-recuperar').submit(e => {
        $('#aviso1').hide();
        $('#aviso').hide();
        mostrar_loader('recuperar_password');
        let email = $('#email-recuperar').val();
        let dui = $('#dui-recuperar').val();
        if (email == '' || dui == '') {
            $('#aviso').show();
            $('#aviso').text('Complete todos los campos');
            cerrar_loader('')
        }else{
            $('#aviso').hide();
            let funcion = 'verificar';
            $.post('../controlador/RecuperarController.php',{funcion, email, dui}, (response) => {
                if (response == 'encontrado') {
                    let funcion = 'recuperar';
                    $('#aviso').hide();
                    $.post('../controlador/RecuperarController.php',{funcion, email, dui}, (response2) => {
                        $('#aviso1').hide();
                        $('#aviso').hide();
                        if (response2 == 'enviado') {
                            cerrar_loader('exito_envio');
                            $('#aviso1').show();
                            $('#aviso1').text('Se reestablecio la contrasena');
                            $('#form-recuperar').trigger('reset');
                        }else{
                            cerrar_loader('error_envio');
                            $('#aviso').show();
                            $('#aviso').text('No se pudo reestablecer');
                            $('#form-recuperar').trigger('reset');
                        }
                    });
                }else{
                    cerrar_loader('error_usuario');
                    $('#aviso1').hide();
                    $('#aviso').hide();
                    $('#aviso').show();
                    $('#aviso').text('El correo y el dui no estan asociados o no estan registrados');
                }
            });
        }
        e.preventDefault();
    });

    function mostrar_loader(mensaje){
        var texto = null;
        var mostrar = false;
        switch (mensaje) {
            case 'recuperar_password':
                texto = 'Se esta enviando el correo...';
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
            case 'exito_envio':
                tipo = 'success';
                texto = 'El correo fue enviado correctamente';
                mostrar = true;
            break;
            case 'error_envio':
                tipo = 'error';
                texto = 'El correo no fue enviado, intente de nuevo.';
                mostrar = true;
            break;
            case 'error_usuario':
                tipo = 'error';
                texto = 'El usuario no fue encontrado.';
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