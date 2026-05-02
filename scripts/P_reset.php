<?php
session_start();
try {
    $_srv = $_SERVER['PHP_SELF'];

    include("../connections/pdo.inc.php");
    require("../php_lib/encrypt.inc.php");
    require_once("../php_lib/logSecurityInfo.inc.php");
    require_once("../php_lib/sendMail.inc.php");

    $_ADMIN_EMAIL = "blindmonkey.be@gmail.com";

    $_inhoud = "";
    $_extrajs = "";

    if (isset($_GET['k'])) {
        $_resetKey = addslashes($_GET['k']);
        $_nu = time();

        $_stmt = $_PDO->prepare(
            "SELECT d_user, d_logon
             FROM ts_authentication
             WHERE d_resetKey = :key AND d_resetTimer >= :nu"
        );
        $_stmt->execute(['key' => $_resetKey, 'nu' => $_nu]);

        if ($_stmt->rowCount() > 0) {
            $_row = $_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user']  = $_row['d_user'];
            $_SESSION['logon'] = $_row['d_logon'];
        } else {
            logSecurityInfo("onbekende user", "Ongeldige of verlopen reset-key");
            $_inhoud = "
            <div class='msg-error'>
              <i class='bi bi-x-circle-fill me-2'></i>
              <strong>Invalid or expired link.</strong><br>
              This reset link is no longer valid. Please request a new one.
            </div>
            <a href='../scripts/P_vergeten.php'
               class='btn-primary-5am'
               style='display:block;text-align:center;text-decoration:none;margin-top:1rem;'>
              Request new reset link
            </a>";
            require_once("../smarty/mySmarty.inc.php");
            $_smarty->assign('pagetitle',    'Link Expired');
            $_smarty->assign('pagesubtitle', 'This reset link is no longer valid');
            $_smarty->assign('inhoud',       $_inhoud);
            $_smarty->assign('extrajs',      '');
            $_smarty->display('password.tpl');
            exit;
        }

        $_inhoud = "
        <form action='$_srv' method='post' id='resetForm' class='mt-2'>
          <div class='mb-3'>
            <label class='form-label'>
              <i class='bi bi-lock-fill me-2'></i>New password
            </label>
            <input type='password' id='paswoord' name='paswoord'
              class='form-control' placeholder='New password' required>
            <div class='password-strength' id='strengthBar' style='background:#333;'></div>
            <div class='strength-text' id='strengthText' style='color:rgba(255,255,255,0.4);'></div>
          </div>
          <div class='mb-4'>
            <label class='form-label'>
              <i class='bi bi-lock-fill me-2'></i>Confirm password
            </label>
            <input type='password' id='confirmeer' name='confirmeer'
              class='form-control' placeholder='Repeat password' required>
            <div class='match-msg' id='matchMsg'></div>
          </div>
          <button type='submit' name='submit' id='submitBtn' class='btn-primary-5am' disabled>
            <i class='bi bi-check-circle me-2'></i>Set new password
          </button>
        </form>";

        $_extrajs = "
        const pwd    = document.getElementById('paswoord');
        const conf   = document.getElementById('confirmeer');
        const bar    = document.getElementById('strengthBar');
        const txt    = document.getElementById('strengthText');
        const matchM = document.getElementById('matchMsg');
        const btn    = document.getElementById('submitBtn');

        function checkStrength(p) {
          let s = 0;
          if (p.length >= 8) s++;
          if (/[A-Z]/.test(p)) s++;
          if (/[0-9]/.test(p)) s++;
          if (/[^A-Za-z0-9]/.test(p)) s++;
          return s;
        }

        function update() {
          const p = pwd.value, c = conf.value;
          const s = checkStrength(p);
          const colors = ['#ef4444','#f59e0b','#10b981','#26e3ff'];
          const labels = ['Weak','Fair','Good','Strong'];
          bar.style.background = p.length ? colors[s-1] || '#333' : '#333';
          bar.style.width      = p.length ? (s * 25) + '%' : '0';
          txt.style.color      = p.length ? colors[s-1] || '#333' : 'transparent';
          txt.textContent      = p.length && s > 0 ? labels[s-1] : '';

          if (c.length > 0) {
            matchM.textContent  = (p === c) ? '✓ Passwords match' : '✗ Passwords do not match';
            matchM.style.color  = (p === c) ? '#10b981' : '#ef4444';
          } else {
            matchM.textContent = '';
          }
          btn.disabled = !(p.length >= 6 && p === c);
        }

        pwd.addEventListener('input', update);
        conf.addEventListener('input', update);";

    } elseif (isset($_POST['submit'])) {
        if (!isset($_SESSION['user']) || !isset($_SESSION['logon'])) {
            throw new Exception("Sessie verlopen - probeer opnieuw via de reset-link");
        }

        $_paswoord = encrypt($_POST['paswoord'], $_SESSION['logon']);
        $_user     = $_SESSION['user'];
        $_logon    = $_SESSION['logon'];

        $_upd = $_PDO->prepare(
            "UPDATE ts_authentication
             SET d_paswoord   = :pwd,
                 d_identifier = '',
                 d_token      = '',
                 d_expire     = 0,
                 d_faultCntr  = 0,
                 d_timeOut    = 0,
                 d_resetKey   = '',
                 d_resetTimer = 0
             WHERE d_user = :uid"
        );
        $_upd->execute(['pwd' => $_paswoord, 'uid' => $_user]);

        logSecurityInfo($_logon, "Paswoord succesvol gereset");

        $_domain = ($_SERVER['SERVER_NAME'] === 'localhost')
            ? 'localhost/' . trim(dirname(dirname($_SERVER['PHP_SELF'])), '/')
            : $_SERVER['HTTP_HOST'];

        $_userBody = "
        <div style='font-family:Arial,sans-serif;max-width:500px;margin:0 auto;background:#0a0a0a;padding:2rem;border-radius:12px;color:#fff;'>
          <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey' style='height:50px;margin-bottom:1.5rem;border-radius:6px;'>
          <h2 style='color:#26e3ff;'>Password Changed</h2>
          <p>Your admin account password was successfully changed on <strong>" . date('d-m-Y H:i:s') . "</strong>.</p>
          <p>If you did not make this change, contact the administrator immediately.</p>
          <p style='color:rgba(255,255,255,0.5);font-size:0.85rem;'>Blind Monkey Admin System</p>
        </div>";
        sendMail($_logon, "Blind Monkey - Password Changed", $_userBody);
        sendMail($_ADMIN_EMAIL, "Blind Monkey - Password Changed: $_logon",
            "<p>Password was reset for account: <strong>$_logon</strong></p><p>Time: " . date('d-m-Y H:i:s') . "</p>");

        session_destroy();
        header("Location:../scripts/A_logon.php?msg=Password+successfully+changed");
        exit;

    } else {
        throw new Exception("Illegal access");
    }

    require_once("../smarty/mySmarty.inc.php");
    $_smarty->assign('pagetitle',    'Reset Password');
    $_smarty->assign('pagesubtitle', 'Choose a strong new password');
    $_smarty->assign('inhoud',       $_inhoud);
    $_smarty->assign('extrajs',      $_extrajs);
    $_smarty->display('password.tpl');

} catch (Exception $_e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($_e, "../logs/error_log.csv");
}
