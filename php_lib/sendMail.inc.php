<?php
function sendMail($_to, $_onderwerp, $_bericht, $_header = "")
{
    if ($_SERVER['SERVER_NAME'] != "localhost")
    {
        // ── Production: PHPMailer via SMTP (one.com) ────────────────────────
        $_logDir  = dirname(__FILE__) . '/../logs/';
        $_pmBase  = dirname(__FILE__) . '/phpmailer/src/';
        $_mailCfg = dirname(__FILE__) . '/../connections/mail.inc.php';

        $_missing = [];
        foreach (['Exception.php', 'PHPMailer.php', 'SMTP.php'] as $_f) {
            if (!file_exists($_pmBase . $_f)) $_missing[] = 'php_lib/phpmailer/src/' . $_f;
        }
        if (!file_exists($_mailCfg)) $_missing[] = 'connections/mail.inc.php';

        if (!empty($_missing)) {
            $_line = date('Y-m-d H:i:s') . " | MAIL_FAILED | MISSING_FILES: " . implode(', ', $_missing) . "\n";
            @file_put_contents($_logDir . 'mail_errors.log', $_line, FILE_APPEND);
            return;
        }

        require_once $_mailCfg;
        require_once $_pmBase . 'Exception.php';
        require_once $_pmBase . 'PHPMailer.php';
        require_once $_pmBase . 'SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->SMTPDebug  = 2;
            $mail->Debugoutput = function($str, $level) use ($_logDir) {
                @file_put_contents($_logDir . 'mail_debug.log', date('H:i:s') . " [$level] $str\n", FILE_APPEND);
            };
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addReplyTo(MAIL_REPLY_TO);
            $mail->addAddress($_to);

            $mail->isHTML(true);
            $mail->Subject = $_onderwerp;
            $mail->Body    = $_bericht;

            $mail->send();

            $_line = date('Y-m-d H:i:s') . " | MAIL_OK | to=$_to | subject=$_onderwerp\n";
            @file_put_contents($_logDir . 'mail_errors.log', $_line, FILE_APPEND);

        } catch (Exception $e) {
            $_line = date('Y-m-d H:i:s') . " | MAIL_FAILED | to=$_to | subject=$_onderwerp | error=" . $mail->ErrorInfo . "\n";
            @file_put_contents($_logDir . 'mail_errors.log', $_line, FILE_APPEND);
        }
    }
    else
    {
        // ── Localhost: echo para desarrollo ──────────────────────────────────
        echo("<hr>to: $_to<br><br>onderwerp: $_onderwerp<br><br>Bericht: $_bericht<br><br>header: $_header<hr>");
    }
}
