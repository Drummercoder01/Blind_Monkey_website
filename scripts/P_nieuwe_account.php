<?php
session_start();
try {
    $_srv = $_SERVER['PHP_SELF'];

    require_once("../php_lib/sendMail.inc.php");

    $_ADMIN_EMAIL = "blindmonkey.be@gmail.com";

    if (!isset($_POST['submit'])) {
        $_inhoud = "
        <form action='$_srv' method='post' class='mt-2'>
          <div class='mb-3'>
            <label class='form-label'>
              <i class='bi bi-person-fill me-2'></i>Your name
            </label>
            <input type='text' name='naam' class='form-control'
              placeholder='First and last name' required autofocus>
          </div>
          <div class='mb-4'>
            <label class='form-label'>
              <i class='bi bi-envelope-fill me-2'></i>Your email address
            </label>
            <input type='email' name='mail' class='form-control'
              placeholder='your@email.com' required>
          </div>
          <button type='submit' name='submit' class='btn-primary-5am'>
            <i class='bi bi-send me-2'></i>Send access request
          </button>
        </form>";
    } else {
        $_naam = htmlspecialchars(trim($_POST['naam']));
        $_mail = filter_var(trim($_POST['mail']), FILTER_SANITIZE_EMAIL);

        if (!filter_var($_mail, FILTER_VALIDATE_EMAIL) || empty($_naam)) {
            $_inhoud = "<div class='msg-error'>
                <i class='bi bi-x-circle-fill me-2'></i>
                Please fill in all fields correctly.
              </div>
              <a href='$_srv' class='btn-primary-5am' style='display:block;text-align:center;text-decoration:none;margin-top:1rem;'>Try again</a>";
        } else {
            $_domain = ($_SERVER['SERVER_NAME'] === 'localhost')
                ? 'localhost/' . trim(dirname(dirname($_SERVER['PHP_SELF'])), '/')
                : $_SERVER['HTTP_HOST'];

            $_body = "
            <div style='font-family:Arial,sans-serif;background:#0a0a0a;padding:2rem;color:#fff;border-radius:12px;'>
              <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey' style='height:50px;margin-bottom:1.5rem;border-radius:6px;'>
              <h2 style='color:#26e3ff;'>New Admin Access Request</h2>
              <p><strong>Name:</strong> $_naam</p>
              <p><strong>Email:</strong> $_mail</p>
              <p><strong>Time:</strong> " . date('d-m-Y H:i:s') . "</p>
              <hr style='border-color:rgba(255,255,255,0.1);'>
              <p>Create the account via the admin panel: Users section.</p>
            </div>";

            sendMail($_ADMIN_EMAIL, "Blind Monkey - New Account Request: $_naam", $_body);

            $_inhoud = "<div class='msg-success'>
              <i class='bi bi-check-circle-fill me-2'></i>
              <strong>Request sent!</strong><br>
              The administrator will review your request and contact you.
            </div>";
        }
    }

    require_once("../smarty/mySmarty.inc.php");
    $_smarty->assign('pagetitle',    'Request Admin Access');
    $_smarty->assign('pagesubtitle', 'Submit a request to the administrator');
    $_smarty->assign('inhoud',       $_inhoud);
    $_smarty->assign('extrajs',      '');
    $_smarty->display('password.tpl');

} catch (Exception $_e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($_e, "../logs/error_log.csv");
}
