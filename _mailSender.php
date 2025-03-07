<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '_config.php';
require 'modules/vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $mensagem = $_POST["mensagem"];

    $mail = new PHPMailer(true);

    if (!$_POST["nome"] || trim($_POST["nome"]) == '') $erro[] = 'Você deve informar o nome';
    if (!$_POST["email"] || trim($_POST["email"]) == '') $erro[] = 'Você deve informar o e-mail';
    if (!$_POST["telefone"] || trim($_POST["telefone"]) == '') $erro[] = 'Você deve informar o telefone';

    if (!$_POST["produto"] || trim($_POST["produto"]) == '') $erro[] = 'Você deve informar o produto do seu interesse';
    if (!$_POST["como_conheceu"] || trim($_POST["como_conheceu"]) == '') $erro[] = 'Você deve informar o como conheceu a CONTROLAR';

    $body_mail = "Nome: {$_POST["nome"]}<br>E-mail: {$_POST["email"]}<br>Empresa: {$_POST["empresa"]}<br>Telefone: {$_POST["telefone"]}<br><br>Interessado no Produto: {$_POST['produto']}<br><br>Como conheceu: {$_POST['como_conheceu']}";

    switch ($_POST["type"]) {
        case 'orcamento':
            $subject = 'Orçamento #' . date("dmhis") . ' - ' . $_POST['produto'];
            break;

        case 'contato':
            if (!$_POST["texto"] || trim($_POST["texto"]) == '') $erro[] = 'Você deve digitar os detalhes no campo de mensagem';
            $subject = 'Contato #' . date("dmhis") . ' - ' . $_POST['produto'];
            $body_mail .= "<br><br>Mensagem: {$_POST['texto']}";
            break;

        default:
            $erro[] = 'Erro ao definir tipo da mensagem';
            break;
    }




    function verify_recaptcha($token, $secret_key)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret_key,
            'response' => $token
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result, true);
    }

    $recaptchaToken = $_POST['recaptchaToken'];

    if ($recaptchaToken) {
        $verification_result = verify_recaptcha($recaptchaToken, $secretKey);
        if ($verification_result['success'] && $verification_result['score'] >= 0.5) {
            $success = true;
        } else {
            $erro[] = 'Erro ao verificar o reCAPTCHA.';
        }
    } else {
        // Token não recebido
        $erro[] = 'reCAPTCHA - Token não recebido.';
    }

    if ($erro) {
        $erroLine = '';
        foreach ($erro as $er) {
            $erroLine .= $er . '<br>';
        }
        $erroLine = "<div class='alert alert-danger'><b>Erro(s) encontrado(s):</b><br>{$erroLine}</div>";
        echo json_encode(['success' => false, 'message' => $erroLine]);
        exit;
    }

    try {


        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $base_ip = $_SERVER['HTTP_CLIENT_IP'];  // IP do cliente
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $base_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  // IP do proxy
        } else {
            $base_ip = $_SERVER['REMOTE_ADDR'];  // IP direto
        }

        $base_date = date('d/m/Y H:i:s');

        $html = <<<HTML
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Helvetica,Arial,sans-serif;font-size:12px;background:#eee;margin:0px auto;padding:30px 0px;">
    <tbody>
        <tr>
            <td>

                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="background-color:#fff;color:#666;padding:20px 0px;font-size:15px;line-height:22px;border-radius:20px; max-width:600px;">
                    <tbody>

                        <tr>
                            <td>
                                <a href="{$base_url}"><img src="{$base_logo}" alt="" style="display:block; max-width: 250px; height: auto; margin: 30px auto;"></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 30px">

                                {$body_mail}

                            </td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>

        <tr>
            <td>

                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:35px 15px;color:#666666;font-size:12px">
                    <tbody>
                        <tr>
                            <td align="center">
                                <p>
                                    Este é um e-mail automático, enviado através do <a href="{$base_url}" style="text-decoration: none; font-weight: bold;">{$base_name}</a>
                                </p>
    <p>
		IP do remetente: {$base_ip} <br>
		Horário: {$base_date}
	</p>
                                <p style="font-size: 11px; color: #666666; margin: 15px;">
                                    <a href="https://brainatwork.com.br" style="font-size: 10px; color: #666666; text-decoration: none;">powered by Brainatwork TI</a>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>
    </tbody>
</table>
HTML;

        // Configurações do servidor
        $mail->SMTPDebug = 0; // 0 = desativado (para produção), 2 = habilitado (para depuração)
        $mail->isSMTP();
        $mail->Host       = $mail_config['host']; // Seu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = $mail_config['user'];
        $mail->Password   = $mail_config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $mail_config['port'];

        $mail->setFrom($mail_config['mail'], $mail_config['name']);
        // Destinatários
        foreach ($to as $key => $to_mail)
            $mail->addAddress($to_mail);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = mb_encode_mimeheader($subject, 'UTF-8');
        $mail->Body    = $html;


        $mail->send();
        $message = "<div class='alert alert-success text-center'>Mensagem enviada com sucesso!</div>";
        echo json_encode(['success' => true, 'message' => $message]);
    } catch (Exception $e) {
        $message = "<div class='alert alert-success text-center'>Erro ao enviar a mensagem: {$mail->ErrorInfo}</div>";
        echo json_encode(['success' => false, 'message' => $message]);
    }
}
