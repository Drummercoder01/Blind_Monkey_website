<?php
try {
    session_start();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: ../scripts/A_logon.php');
        exit;
    }

    include("../connections/pdo.inc.php");
    require("../php_lib/encrypt.inc.php");
    require_once("../php_lib/logSecurityInfo.inc.php");
    require_once("../php_lib/sendMail.inc.php");

    $_logon  = $_SESSION['logon'];
    $_userId = $_SESSION['user_id'];
    $_srv    = $_SERVER['PHP_SELF'];
    $_modal  = null;

    // ── Handle form submit ──────────────────────────────────────────────────
    if (isset($_POST['submit'])) {

        $_current = $_POST['current_pwd']  ?? '';
        $_new     = $_POST['new_pwd']      ?? '';
        $_confirm = $_POST['confirm_pwd']  ?? '';

        if (empty($_current) || empty($_new) || empty($_confirm)) {
            $_modal = ['type'=>'error','icon'=>'bi-exclamation-triangle-fill',
                       'title'=>'Missing fields',
                       'body'=>'All three fields are required.'];

        } elseif ($_new !== $_confirm) {
            $_modal = ['type'=>'error','icon'=>'bi-exclamation-triangle-fill',
                       'title'=>'Passwords do not match',
                       'body'=>'New password and confirmation must be identical.'];

        } elseif (strlen($_new) < 6) {
            $_modal = ['type'=>'error','icon'=>'bi-exclamation-triangle-fill',
                       'title'=>'Password too short',
                       'body'=>'Password must be at least 6 characters.'];

        } else {
            $_hashedCurrent = encrypt($_current, $_logon);
            $_stmt = $_PDO->prepare(
                "SELECT d_user FROM ts_authentication
                 WHERE d_user = :uid AND d_paswoord = :pwd"
            );
            $_stmt->execute(['uid' => $_userId, 'pwd' => $_hashedCurrent]);

            if ($_stmt->rowCount() === 0) {
                logSecurityInfo($_logon, "Fout huidig paswoord bij wijziging");
                $_modal = ['type'=>'error','icon'=>'bi-shield-exclamation',
                           'title'=>'Incorrect current password',
                           'body'=>'The current password you entered is wrong.'];
            } else {
                $_hashedNew = encrypt($_new, $_logon);
                $_upd = $_PDO->prepare(
                    "UPDATE ts_authentication
                     SET d_paswoord  = :pwd,
                         d_faultCntr = 0,
                         d_timeOut   = 0
                     WHERE d_user = :uid"
                );
                $_upd->execute(['pwd' => $_hashedNew, 'uid' => $_userId]);

                logSecurityInfo($_logon, "Paswoord gewijzigd door gebruiker");

                $_domain = ($_SERVER['SERVER_NAME'] === 'localhost')
                    ? 'localhost/' . trim(dirname(dirname($_SERVER['PHP_SELF'])), '/')
                    : $_SERVER['HTTP_HOST'];

                $_emailBody = "
                <div style='font-family:Arial,sans-serif;max-width:500px;margin:0 auto;
                            background:#0a0a0a;padding:2rem;border-radius:12px;color:#fff;'>
                  <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey'
                       style='height:50px;margin-bottom:1.5rem;'>
                  <h2 style='color:#26e3ff;'>Password Changed</h2>
                  <p>Your password was successfully changed on
                     <strong>" . date('d-m-Y H:i:s') . "</strong>.</p>
                  <p>If you did not make this change, contact the administrator immediately.</p>
                  <p style='color:rgba(255,255,255,0.5);font-size:0.85rem;'>Blind Monkey Admin System</p>
                </div>";
                sendMail($_logon, "Blind Monkey - Password Changed", $_emailBody);

                $_modal = ['type'=>'success','icon'=>'bi-check-circle-fill',
                           'title'=>'Password updated',
                           'body'=>'Your password has been changed successfully.'];
            }
        }
    }

    // ── Build UI ────────────────────────────────────────────────────────────
    $_modalHtml = '';
    if ($_modal) {
        $_bg      = $_modal['type'] === 'success' ? 'rgba(16,185,129,.15)'  : 'rgba(239,68,68,.15)';
        $_border  = $_modal['type'] === 'success' ? 'rgba(16,185,129,.4)'   : 'rgba(239,68,68,.4)';
        $_color   = $_modal['type'] === 'success' ? '#10b981'               : '#ef4444';
        $_btnClr  = $_modal['type'] === 'success' ? '#10b981'               : '#ef4444';
        $_btnBg   = $_modal['type'] === 'success' ? 'rgba(16,185,129,.15)'  : 'rgba(239,68,68,.15)';
        $_modalHtml = "
        <div id='cpModal' style='position:fixed;inset:0;background:rgba(0,0,0,.7);
             backdrop-filter:blur(6px);z-index:9999;display:flex;
             align-items:center;justify-content:center;padding:1rem;'>
          <div style='background:#1a1f2e;border:1px solid $_border;border-radius:18px;
                      max-width:420px;width:100%;padding:2rem;text-align:center;
                      box-shadow:0 32px 80px rgba(0,0,0,.7);'>
            <div style='width:56px;height:56px;border-radius:50%;
                        background:$_bg;border:2px solid $_border;
                        display:flex;align-items:center;justify-content:center;
                        margin:0 auto 1rem;font-size:1.5rem;color:$_color;'>
              <i class='bi {$_modal['icon']}'></i>
            </div>
            <h5 style='color:#fff;font-weight:700;margin-bottom:.5rem;'>{$_modal['title']}</h5>
            <p style='color:rgba(255,255,255,.6);font-size:.9rem;margin-bottom:1.5rem;'>{$_modal['body']}</p>
            <button onclick=\"document.getElementById('cpModal').remove()\"
              style='background:$_btnBg;color:$_btnClr;border:1px solid $_border;
                     border-radius:10px;padding:.6rem 1.5rem;font-weight:600;
                     cursor:pointer;font-size:.9rem;'>OK</button>
          </div>
        </div>";
    }

    $_inhoud = "
    <style>
      .cp-header {
        background:linear-gradient(135deg,rgba(38,227,255,.1),rgba(38,227,255,.05));
        border-bottom:2px solid rgba(38,227,255,.2);
        padding:2rem 0;margin-bottom:2rem;text-align:center;
      }
      .cp-header h1 { font-size:2rem;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:1px; }
      .cp-header p  { color:rgba(38,227,255,.8);margin:0;font-size:.92rem; }
      .cp-card {
        background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
        border-radius:16px;padding:2rem;max-width:480px;margin:0 auto;
      }
      .cp-card .form-label { color:rgba(255,255,255,.7);font-size:.88rem;font-weight:500; }
      .cp-card .form-control {
        background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);
        color:#fff;border-radius:10px;padding:.75rem 1rem;font-size:.9rem;
      }
      .cp-card .form-control:focus {
        background:rgba(255,255,255,.1);border-color:rgba(38,227,255,.5);
        box-shadow:0 0 0 3px rgba(38,227,255,.1);color:#fff;
      }
      .cp-card .form-control::placeholder { color:rgba(255,255,255,.25); }
      .password-strength {
        height:4px;border-radius:2px;margin-top:.5rem;
        transition:all .3s ease;width:0;background:#333;
      }
      .strength-text { font-size:.78rem;margin-top:.3rem;min-height:1.1em; }
      .match-msg     { font-size:.78rem;margin-top:.3rem;min-height:1.1em; }
      .btn-save {
        background:linear-gradient(135deg,#26e3ff,#1bc4dd);
        color:#000;border:none;border-radius:10px;
        padding:.75rem 2rem;font-weight:700;font-size:.95rem;
        cursor:pointer;width:100%;margin-top:.5rem;transition:all .2s;
      }
      .btn-save:hover:not(:disabled) { filter:brightness(1.1);transform:translateY(-1px); }
      .btn-save:disabled { opacity:.4;cursor:not-allowed;transform:none; }
      .divider { border-color:rgba(255,255,255,.08);margin:1.5rem 0; }
      .pw-wrap { position:relative; }
      .pw-wrap .form-control { padding-right:2.8rem; }
      .pw-eye {
        position:absolute;top:50%;right:.85rem;transform:translateY(-50%);
        background:none;border:none;color:rgba(255,255,255,.35);
        cursor:pointer;padding:0;font-size:1rem;line-height:1;
        transition:color .15s;
      }
      .pw-eye:hover { color:#26e3ff; }
    </style>

    $_modalHtml

    <div class='cp-header'>
      <h1><i class='bi bi-key-fill me-2'></i>Change Password</h1>
      <p>Logged in as <strong style='color:#26e3ff;'>$_logon</strong></p>
    </div>

    <div class='container'>
      <div class='cp-card'>
        <form method='post' action='$_srv' id='cpForm'>

          <div class='mb-3'>
            <label class='form-label'>
              <i class='bi bi-lock me-1'></i>Current password
            </label>
            <div class='pw-wrap'>
              <input type='password' name='current_pwd' id='currentPwd' class='form-control'
                     placeholder='Your current password' required autocomplete='current-password'>
              <button type='button' class='pw-eye' onclick='togglePw(\"currentPwd\",this)' tabindex='-1'>
                <i class='bi bi-eye'></i>
              </button>
            </div>
          </div>

          <hr class='divider'>

          <div class='mb-3'>
            <label class='form-label'>
              <i class='bi bi-lock-fill me-1'></i>New password
            </label>
            <div class='pw-wrap'>
              <input type='password' name='new_pwd' id='newPwd' class='form-control'
                     placeholder='New password' required autocomplete='new-password'>
              <button type='button' class='pw-eye' onclick='togglePw(\"newPwd\",this)' tabindex='-1'>
                <i class='bi bi-eye'></i>
              </button>
            </div>
            <div class='password-strength' id='strengthBar'></div>
            <div class='strength-text'     id='strengthText'></div>
          </div>

          <div class='mb-4'>
            <label class='form-label'>
              <i class='bi bi-lock-fill me-1'></i>Confirm new password
            </label>
            <div class='pw-wrap'>
              <input type='password' name='confirm_pwd' id='confirmPwd' class='form-control'
                     placeholder='Repeat new password' required autocomplete='new-password'>
              <button type='button' class='pw-eye' onclick='togglePw(\"confirmPwd\",this)' tabindex='-1'>
                <i class='bi bi-eye'></i>
              </button>
            </div>
            <div class='match-msg' id='matchMsg'></div>
          </div>

          <button type='submit' name='submit' id='saveBtn' class='btn-save' disabled>
            <i class='bi bi-check-circle me-2'></i>Save new password
          </button>

        </form>
      </div>
    </div>

    <script>
      const newPwd  = document.getElementById('newPwd');
      const confPwd = document.getElementById('confirmPwd');
      const bar     = document.getElementById('strengthBar');
      const txt     = document.getElementById('strengthText');
      const matchM  = document.getElementById('matchMsg');
      const btn     = document.getElementById('saveBtn');

      function checkStrength(p) {
        let s = 0;
        if (p.length >= 8) s++;
        if (/[A-Z]/.test(p)) s++;
        if (/[0-9]/.test(p)) s++;
        if (/[^A-Za-z0-9]/.test(p)) s++;
        return s;
      }

      function update() {
        const p = newPwd.value, c = confPwd.value;
        const s = checkStrength(p);
        const colors = ['#ef4444','#f59e0b','#10b981','#26e3ff'];
        const labels = ['Weak','Fair','Good','Strong'];
        bar.style.background = p.length ? (colors[s-1] || '#333') : '#333';
        bar.style.width      = p.length ? (s * 25) + '%' : '0';
        txt.style.color      = p.length ? (colors[s-1] || '#333') : 'transparent';
        txt.textContent      = p.length && s > 0 ? labels[s-1] : '';

        if (c.length > 0) {
          matchM.textContent = (p === c) ? '✓ Match' : '✗ Do not match';
          matchM.style.color = (p === c) ? '#10b981' : '#ef4444';
        } else {
          matchM.textContent = '';
        }
        btn.disabled = !(p.length >= 6 && p === c);
      }

      newPwd.addEventListener('input',  update);
      confPwd.addEventListener('input', update);

      function togglePw(id, btn) {
        const input = document.getElementById(id);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
          input.type    = 'text';
          icon.className = 'bi bi-eye-slash';
          btn.style.color = '#26e3ff';
        } else {
          input.type    = 'password';
          icon.className = 'bi bi-eye';
          btn.style.color = '';
        }
      }
    </script>";

    $_jsInclude = [];
    require("../code/output_admin.inc.php");

} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
