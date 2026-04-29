<?php
session_start();
try {
    $_srv = $_SERVER['PHP_SELF'];

    require_once("../connections/pdo.inc.php");
    require_once("../php_lib/encrypt.inc.php");
    require_once("../php_lib/sendMail.inc.php");
    require_once("../php_lib/logSecurityInfo.inc.php");

    $_domain = ($_SERVER['SERVER_NAME'] === 'localhost')
        ? 'localhost/' . trim(dirname(dirname($_SERVER['PHP_SELF'])), '/')
        : $_SERVER['HTTP_HOST'];

    $_ADMIN_EMAIL = "blindmonkey.be@gmail.com";

    if (!isset($_POST['submit'])) {
        $_inhoud = "
        <form action='$_srv' method='post' class='mt-2'>
          <div class='mb-4'>
            <label class='form-label'>
              <i class='bi bi-envelope-fill me-2'></i>Your email address
            </label>
            <input type='email' name='mail' class='form-control'
              placeholder='your@email.com' required autofocus>
          </div>
          <button type='submit' name='submit' class='btn-primary-5am'>
            <i class='bi bi-send me-2'></i>Send reset link
          </button>
        </form>";
    } else {
        $_mail = filter_var(trim($_POST['mail']), FILTER_SANITIZE_EMAIL);

        if (!filter_var($_mail, FILTER_VALIDATE_EMAIL)) {
            logSecurityInfo("onbekend", "Ongeldig e-mailadres bij password reset: $_mail");
            $_inhoud = "<div class='msg-error'>
                <i class='bi bi-x-circle-fill me-2'></i>
                Please enter a valid email address.
              </div>
              <a href='$_srv' class='btn-primary-5am' style='display:block;text-align:center;text-decoration:none'>Try again</a>";
        } else {
            $_sql = $_PDO->prepare(
                "SELECT d_user FROM ts_authentication WHERE d_logon = :mail"
            );
            $_sql->execute(['mail' => $_mail]);

            if ($_sql->rowCount() > 0) {
                $_row  = $_sql->fetch(PDO::FETCH_ASSOC);
                $_user = $_row['d_user'];

                $_nu        = time();
                $_resetKey  = encrypt("$_mail $_user $_nu", "webo");
                $_resetTime = time() + (60 * 60 * 1);

                $_upd = $_PDO->prepare(
                    "UPDATE ts_authentication
                     SET d_resetKey = :key, d_resetTimer = :timer
                     WHERE d_user = :uid"
                );
                $_upd->execute(['key' => $_resetKey, 'timer' => $_resetTime, 'uid' => $_user]);

                $_link = "http://$_domain/scripts/P_reset.php?k=$_resetKey";
                $_body = "
                <div style='font-family:Arial,sans-serif;max-width:500px;margin:0 auto;background:#0a0a0a;padding:2rem;border-radius:12px;color:#fff;'>
                  <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey' style='height:50px;margin-bottom:1.5rem;border-radius:6px;'>
                  <h2 style='color:#26e3ff;'>Password Reset</h2>
                  <p>You requested a password reset for your admin account.</p>
                  <p>Click the button below within <strong>1 hour</strong>:</p>
                  <a href='$_link'
                     style='display:inline-block;background:#26e3ff;color:#000;padding:0.75rem 2rem;border-radius:8px;text-decoration:none;font-weight:700;margin:1rem 0;'>
                    Reset my password
                  </a>
                  <p style='color:rgba(255,255,255,0.5);font-size:0.85rem;'>
                    If you did not request this, ignore this email.
                  </p>
                </div>";

                sendMail($_mail, "Blind Monkey - Password Reset", $_body);
                sendMail($_ADMIN_EMAIL, "Blind Monkey - Reset Request: $_mail",
                    "<p>Password reset requested for account: <strong>$_mail</strong></p><p>Time: " . date('d-m-Y H:i:s') . "</p>");
                logSecurityInfo($_mail, "Password reset aangevraagd");

                $_inhoud = "<div class='msg-success'>
                  <i class='bi bi-check-circle-fill me-2'></i>
                  <strong>Email sent!</strong><br>
                  Check your inbox for the reset link (valid 1 hour).
                </div>";
            } else {
                logSecurityInfo($_mail, "Password reset - email niet gevonden");
                // Don't reveal whether email exists
                $_inhoud = "<div class='msg-success'>
                  <i class='bi bi-check-circle-fill me-2'></i>
                  <strong>Email sent!</strong><br>
                  If this address is registered you will receive a reset link.
                </div>";
            }
        }
    }

    require_once("../smarty/mySmarty.inc.php");
    $_smarty->assign('pagetitle',    'Forgot Password');
    $_smarty->assign('pagesubtitle', 'Enter your email to receive a reset link');
    $_smarty->assign('inhoud',       $_inhoud);
    $_smarty->assign('extrajs',      '');
    $_smarty->display('password.tpl');

} catch (Exception $_e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($_e, "../logs/error_log.csv");
}
