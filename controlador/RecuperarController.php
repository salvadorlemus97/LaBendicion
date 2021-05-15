<?php
    include_once '../modelo/Usuario.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require '../vendor/autoload.php';
    $usuario = new Usuario();
    if ($_POST['funcion'] == 'verificar') {
        $email = $_POST['email'];
        $dui = $_POST['dui'];
        $usuario->verificar($email, $dui);
    }

    if ($_POST['funcion'] == 'recuperar') {
        $email = $_POST['email'];
        $dui = $_POST['dui'];
        $codigo = generar(10);
        $usuario->reemplazar($codigo, $email, $dui);

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'correo@gmail.com';                     // SMTP username
            $mail->Password   = 'micontrasena';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            // $mail->SMTPSecure = 'tls';         // tls Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // 587 TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('correo@gmail.com', 'nombre');
            $mail->addAddress($email);     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reestablecer contrasena';
            $mail->Body    = 'Tu nueva contrasena es: <b>'.$codigo.'</b>';
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->SMTPDebug = false;
            $mail->do_debug = false;

            // desactiva la verificacion ssl para conectar con el server smtp
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->send();
            echo 'enviado';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function generar($longitud){
        $key = '';
        $patron = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($patron)-1;
        for ($i=0; $i < $longitud; $i++) { 
            $key .= $patron{mt_rand(0, $max)};
        }
        return $key;
    }