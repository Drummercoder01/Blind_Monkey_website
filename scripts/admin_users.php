<?php
try {
    session_start();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true
        || !isset($_SESSION['rol']) || (int)$_SESSION['rol'] !== 1) {
        header('Location: ../scripts/A_logon.php');
        exit;
    }

    require("../connections/pdo.inc.php");
    require("../php_lib/encrypt.inc.php");
    require_once("../php_lib/logSecurityInfo.inc.php");
    require_once("../php_lib/sendMail.inc.php");

    $_ADMIN_EMAIL  = "blindmonkey.be@gmail.com";
    $_currentLogon = $_SESSION['logon'];

    $_domain = ($_SERVER['SERVER_NAME'] === 'localhost')
        ? 'localhost/' . trim(dirname(dirname($_SERVER['PHP_SELF'])), '/')
        : $_SERVER['HTTP_HOST'];

    $_modal = null;

    function generateTempPassword($length = 12) {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#';
        $pwd   = '';
        for ($i = 0; $i < $length; $i++) {
            $pwd .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $pwd;
    }

    // ── Handle POST ─────────────────────────────────────────────────────────
    if (isset($_POST['action'])) {
        $_action = $_POST['action'];

        // ── Create ─────────────────────────────────────────────────────────
        if ($_action === 'create') {
            $_newLogon = filter_var(trim($_POST['logon']), FILTER_SANITIZE_EMAIL);
            $_newRol   = (isset($_POST['rol']) && (int)$_POST['rol'] === 1) ? 1 : 0;

            if (!filter_var($_newLogon, FILTER_VALIDATE_EMAIL)) {
                $_modal = ['type'=>'error','icon'=>'bi-x-circle-fill',
                           'title'=>'Invalid Email',
                           'body'=>'Please enter a valid email address.'];
            } else {
                $_chk = $_PDO->prepare("SELECT d_user FROM ts_authentication WHERE d_logon = :logon");
                $_chk->execute(['logon' => $_newLogon]);

                if ($_chk->rowCount() > 0) {
                    $_modal = ['type'=>'error','icon'=>'bi-x-circle-fill',
                               'title'=>'Already Registered',
                               'body'=>"The email <strong>$_newLogon</strong> is already in the system."];
                } else {
                    $_tempPwd    = generateTempPassword();
                    $_encPwd     = encrypt($_tempPwd, $_newLogon);
                    $_identifier = encrypt($_newLogon, $_newLogon);
                    $_token      = encrypt(uniqid(rand(), true));
                    $rolLabel    = $_newRol === 1 ? 'Admin' : 'Inactive';

                    $_ins = $_PDO->prepare(
                        "INSERT INTO ts_authentication
                         (d_logon, d_paswoord, d_identifier, d_token, d_rol, d_faultCntr, d_timeOut, d_expire)
                         VALUES (:logon,:pwd,:ident,:token,:rol,0,0,0)"
                    );
                    $_ins->execute(['logon'=>$_newLogon,'pwd'=>$_encPwd,
                                    'ident'=>$_identifier,'token'=>$_token,'rol'=>$_newRol]);

                    $_userBody = "
                    <div style='font-family:Arial,sans-serif;background:#0a0a0a;padding:2rem;color:#fff;border-radius:12px;max-width:500px;'>
                      <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey' style='height:50px;margin-bottom:1.5rem;border-radius:6px;'>
                      <h2 style='color:#26e3ff;'>Welcome to Blind Monkey Admin</h2>
                      <p>An admin account has been created for you.</p>
                      <table style='width:100%;'>
                        <tr><td style='color:rgba(255,255,255,0.6);padding:0.3rem 0;'>Email (login)</td>
                            <td><strong>$_newLogon</strong></td></tr>
                        <tr><td style='color:rgba(255,255,255,0.6);padding:0.3rem 0;'>Temp password</td>
                            <td style='color:#26e3ff;font-size:1.1rem;'><strong>$_tempPwd</strong></td></tr>
                        <tr><td style='color:rgba(255,255,255,0.6);padding:0.3rem 0;'>Role</td>
                            <td>$rolLabel</td></tr>
                      </table>
                      <a href='http://$_domain/scripts/A_logon.php'
                         style='display:inline-block;margin-top:1.5rem;background:#26e3ff;color:#000;
                                padding:0.6rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:700;'>
                        Login now
                      </a>
                    </div>";
                    sendMail($_newLogon, "Blind Monkey - Your Admin Account", $_userBody);
                    sendMail($_ADMIN_EMAIL, "Blind Monkey - New Account: $_newLogon",
                        "<p>New account by <strong>$_currentLogon</strong>: <strong>$_newLogon</strong> (role: $rolLabel) — " . date('d-m-Y H:i:s') . "</p>");
                    logSecurityInfo($_currentLogon, "Nieuw account: $_newLogon (rol: $_newRol)");

                    $_modal = ['type'=>'success','icon'=>'bi-person-check-fill',
                               'title'=>'Account Created',
                               'body'=>"Account <strong>$_newLogon</strong> created successfully.<br>Role: <strong>$rolLabel</strong>",
                               'extra'=>"Temporary password sent by email."];
                }
            }
        }

        // ── Toggle activate/deactivate ─────────────────────────────────────
        elseif ($_action === 'toggle') {
            $_uid    = (int)$_POST['uid'];
            $_newRol = (int)$_POST['new_rol'];

            if ($_uid === (int)$_SESSION['user_id'] && $_newRol === 0) {
                $_modal = ['type'=>'error','icon'=>'bi-shield-x',
                           'title'=>'Not Allowed',
                           'body'=>'You cannot deactivate your own account.'];
            } else {
                $_upd = $_PDO->prepare("UPDATE ts_authentication SET d_rol = :rol WHERE d_user = :uid");
                $_upd->execute(['rol'=>$_newRol,'uid'=>$_uid]);

                $_sel = $_PDO->prepare("SELECT d_logon FROM ts_authentication WHERE d_user = :uid");
                $_sel->execute(['uid'=>$_uid]);
                $_tgt = $_sel->fetch(PDO::FETCH_ASSOC);
                $_tgtLogon = $_tgt ? $_tgt['d_logon'] : "user #$_uid";

                $_statusLabel = ($_newRol === 1) ? 'activated' : 'deactivated';
                logSecurityInfo($_currentLogon, "Account $_tgtLogon $_statusLabel");

                $_statusMsg = ($_newRol === 1)
                    ? 'Your account has been <strong style="color:#10b981;">activated</strong>. You can now login.'
                    : 'Your account has been <strong style="color:#ef4444;">deactivated</strong>. Contact the admin.';
                $_userBody = "
                <div style='font-family:Arial,sans-serif;background:#0a0a0a;padding:2rem;color:#fff;border-radius:12px;'>
                  <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey' style='height:50px;margin-bottom:1.5rem;border-radius:6px;'>
                  <h2 style='color:#26e3ff;'>Account Status Changed</h2>
                  <p>$_statusMsg</p>
                  <p style='color:rgba(255,255,255,0.4);font-size:0.8rem;'>" . date('d-m-Y H:i:s') . "</p>
                </div>";
                sendMail($_tgtLogon, "Blind Monkey - Account Status Update", $_userBody);
                sendMail($_ADMIN_EMAIL, "Blind Monkey - Account $_statusLabel: $_tgtLogon",
                    "<p><strong>$_tgtLogon</strong> $_statusLabel by <strong>$_currentLogon</strong> — " . date('d-m-Y H:i:s') . "</p>");

                $_icon = ($_newRol === 1) ? 'bi-play-circle-fill' : 'bi-pause-circle-fill';
                $_type = ($_newRol === 1) ? 'success' : 'warning';
                $_modal = ['type'=>$_type,'icon'=>$_icon,
                           'title'=>'Account ' . ucfirst($_statusLabel),
                           'body'=>"Account <strong>$_tgtLogon</strong> has been <strong>$_statusLabel</strong>.",
                           'extra'=>'Notification email sent to the user.'];
            }
        }

        // ── Reset password by admin ────────────────────────────────────────
        elseif ($_action === 'reset_pwd') {
            $_uid = (int)$_POST['uid'];
            $_sel = $_PDO->prepare("SELECT d_logon FROM ts_authentication WHERE d_user = :uid");
            $_sel->execute(['uid'=>$_uid]);
            $_tgt = $_sel->fetch(PDO::FETCH_ASSOC);

            if ($_tgt) {
                $_tgtLogon = $_tgt['d_logon'];
                $_tempPwd  = generateTempPassword();
                $_encPwd   = encrypt($_tempPwd, $_tgtLogon);

                $_upd = $_PDO->prepare(
                    "UPDATE ts_authentication
                     SET d_paswoord=:pwd, d_faultCntr=0, d_timeOut=0, d_resetKey='', d_resetTimer=0
                     WHERE d_user=:uid"
                );
                $_upd->execute(['pwd'=>$_encPwd,'uid'=>$_uid]);
                logSecurityInfo($_currentLogon, "Admin reset paswoord voor: $_tgtLogon");

                $_userBody = "
                <div style='font-family:Arial,sans-serif;background:#0a0a0a;padding:2rem;color:#fff;border-radius:12px;max-width:500px;'>
                  <img src='http://$_domain/img/blind_monkey_logo.jpg' alt='Blind Monkey' style='height:50px;margin-bottom:1.5rem;border-radius:6px;'>
                  <h2 style='color:#26e3ff;'>Password Reset by Admin</h2>
                  <p>Your password was reset by an administrator on " . date('d-m-Y H:i:s') . ".</p>
                  <p style='font-size:0.95rem;'>New temporary password:</p>
                  <p style='font-size:1.4rem;font-weight:700;color:#26e3ff;
                             background:rgba(38,227,255,0.1);padding:0.75rem 1rem;border-radius:8px;
                             letter-spacing:2px;'>$_tempPwd</p>
                  <a href='http://$_domain/scripts/A_logon.php'
                     style='display:inline-block;margin-top:1rem;background:#26e3ff;color:#000;
                            padding:0.6rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:700;'>
                    Login now
                  </a>
                  <p style='color:rgba(255,255,255,0.4);font-size:0.8rem;margin-top:1rem;'>
                    Please change your password after first login.
                  </p>
                </div>";
                sendMail($_tgtLogon, "Blind Monkey - Password Reset by Admin", $_userBody);
                sendMail($_ADMIN_EMAIL, "Blind Monkey - Admin Password Reset: $_tgtLogon",
                    "<p>Password reset by <strong>$_currentLogon</strong> for <strong>$_tgtLogon</strong> — " . date('d-m-Y H:i:s') . "</p>");

                $_modal = ['type'=>'success','icon'=>'bi-key-fill',
                           'title'=>'Password Reset',
                           'body'=>"Password for <strong>$_tgtLogon</strong> has been reset.",
                           'extra'=>"<div style='margin-top:0.75rem;'>
                             <span style='font-size:0.85rem;color:rgba(255,255,255,0.6);'>New temporary password:</span><br>
                             <span style='font-size:1.3rem;font-weight:700;color:#26e3ff;
                                          background:rgba(38,227,255,0.1);padding:0.4rem 0.8rem;
                                          border-radius:6px;display:inline-block;margin-top:0.3rem;
                                          letter-spacing:2px;'>$_tempPwd</span>
                           </div>"];
            }
        }

        // ── Delete ─────────────────────────────────────────────────────────
        elseif ($_action === 'delete') {
            $_uid = (int)$_POST['uid'];

            if ($_uid === (int)$_SESSION['user_id']) {
                $_modal = ['type'=>'error','icon'=>'bi-shield-x',
                           'title'=>'Not Allowed',
                           'body'=>'You cannot delete your own account.'];
            } else {
                $_sel = $_PDO->prepare("SELECT d_logon FROM ts_authentication WHERE d_user = :uid");
                $_sel->execute(['uid'=>$_uid]);
                $_tgt = $_sel->fetch(PDO::FETCH_ASSOC);
                $_tgtLogon = $_tgt ? $_tgt['d_logon'] : "user #$_uid";

                $_del = $_PDO->prepare("DELETE FROM ts_authentication WHERE d_user = :uid");
                $_del->execute(['uid'=>$_uid]);
                logSecurityInfo($_currentLogon, "Account verwijderd: $_tgtLogon");
                sendMail($_ADMIN_EMAIL, "Blind Monkey - Account Deleted: $_tgtLogon",
                    "<p><strong>$_tgtLogon</strong> deleted by <strong>$_currentLogon</strong> — " . date('d-m-Y H:i:s') . "</p>");

                $_modal = ['type'=>'warning','icon'=>'bi-trash-fill',
                           'title'=>'Account Deleted',
                           'body'=>"Account <strong>$_tgtLogon</strong> has been permanently deleted.",
                           'extra'=>'Admin notification email sent.'];
            }
        }
    }

    // ── Fetch users ─────────────────────────────────────────────────────────
    $_users = $_PDO->query(
        "SELECT d_user, d_logon, d_rol FROM ts_authentication ORDER BY d_rol DESC, d_logon ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    $_jsInclude = [];

    // ── Build HTML ──────────────────────────────────────────────────────────
    $_inhoud = "
    <style>
      .users-container { padding:0; }
      .page-header {
        background: linear-gradient(135deg,rgba(38,227,255,0.1),rgba(38,227,255,0.05));
        border-bottom: 2px solid rgba(38,227,255,0.2);
        padding: 2rem 0; margin-bottom: 2rem; text-align:center;
      }
      .page-header h1 { font-size:2rem;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:1px; }
      .page-header p  { color:rgba(38,227,255,0.8);margin:0;font-size:0.95rem; }
      .card-5am {
        background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);
        border-radius:16px;padding:1.5rem;margin-bottom:1.5rem;
      }
      .card-5am h5 { color:#26e3ff;font-weight:700;margin-bottom:1rem;font-size:1.05rem; }
      .users-table { width:100%;border-collapse:collapse; }
      .users-table th { color:rgba(255,255,255,0.5);font-size:0.78rem;text-transform:uppercase;
                         letter-spacing:0.5px;padding:0.5rem 0.75rem;border-bottom:1px solid rgba(255,255,255,0.1); }
      .users-table td { padding:0.75rem;border-bottom:1px solid rgba(255,255,255,0.05);color:#fff;font-size:0.9rem; }
      .users-table tr:last-child td { border-bottom:none; }
      .badge-active   { background:rgba(16,185,129,0.2);color:#10b981;border:1px solid rgba(16,185,129,0.4);
                         padding:.2rem .6rem;border-radius:20px;font-size:.75rem;font-weight:600; }
      .badge-inactive { background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3);
                         padding:.2rem .6rem;border-radius:20px;font-size:.75rem;font-weight:600; }
      .btn-5am { border:none;border-radius:8px;padding:.3rem .7rem;font-size:.8rem;font-weight:600;cursor:pointer;transition:all .2s; }
      .btn-activate   { background:rgba(16,185,129,.2);color:#10b981;border:1px solid rgba(16,185,129,.3); }
      .btn-deactivate { background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.3); }
      .btn-reset-pwd  { background:rgba(38,227,255,.1);color:#26e3ff;border:1px solid rgba(38,227,255,.3); }
      .btn-delete     { background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.3); }
      .btn-5am:hover  { filter:brightness(1.3);transform:translateY(-1px); }
      .form-ctrl-5am { background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);color:#fff;
                         border-radius:8px;padding:.5rem .75rem;width:100%;font-size:.9rem; }
      .form-ctrl-5am:focus { outline:none;border-color:#26e3ff;box-shadow:0 0 0 2px rgba(38,227,255,.15); }
      .form-ctrl-5am option { background:#1a1a1a; }
      label.lbl { color:rgba(255,255,255,.7);font-size:.85rem;display:block;margin-bottom:.3rem; }
      .btn-create { background:linear-gradient(135deg,#26e3ff,#1a9fb8);color:#000;border:none;
                     border-radius:10px;padding:.6rem 1.5rem;font-weight:700;font-size:.9rem;cursor:pointer;transition:all .3s; }
      .btn-create:hover { filter:brightness(1.1);transform:translateY(-2px); }

      /* ── Notification Modal ── */
      .notif-backdrop {
        position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);
        z-index:9999;display:flex;align-items:center;justify-content:center;
        animation:fadeInBd .2s ease;
      }
      @keyframes fadeInBd { from{opacity:0} to{opacity:1} }
      .notif-modal {
        background:linear-gradient(135deg,rgba(15,20,30,.98),rgba(10,15,25,.98));
        border:1px solid rgba(255,255,255,.12);border-radius:20px;
        padding:2rem 2rem 1.5rem;max-width:460px;width:90%;position:relative;
        animation:slideUp .25s cubic-bezier(.34,1.56,.64,1);
        box-shadow:0 24px 64px rgba(0,0,0,.6),0 0 0 1px rgba(255,255,255,.05);
      }
      @keyframes slideUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
      .notif-icon-wrap {
        width:52px;height:52px;border-radius:14px;display:flex;align-items:center;
        justify-content:center;margin-bottom:1rem;font-size:1.4rem;
      }
      .notif-icon-wrap.success { background:rgba(16,185,129,.2);color:#10b981;border:1px solid rgba(16,185,129,.3); }
      .notif-icon-wrap.error   { background:rgba(239,68,68,.2);color:#ef4444;border:1px solid rgba(239,68,68,.3); }
      .notif-icon-wrap.warning { background:rgba(245,158,11,.2);color:#f59e0b;border:1px solid rgba(245,158,11,.3); }
      .notif-title { font-size:1.2rem;font-weight:700;color:#fff;margin-bottom:.4rem; }
      .notif-body  { color:rgba(255,255,255,.75);font-size:.92rem;line-height:1.5; }
      .notif-extra { margin-top:.75rem;padding-top:.75rem;border-top:1px solid rgba(255,255,255,.08); }
      .notif-close {
        position:absolute;top:1rem;right:1rem;background:rgba(255,255,255,.08);border:none;
        color:rgba(255,255,255,.6);border-radius:8px;width:32px;height:32px;font-size:1rem;
        cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;
      }
      .notif-close:hover { background:rgba(255,255,255,.15);color:#fff; }
      .notif-btn-close {
        margin-top:1.25rem;width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);
        color:#fff;border-radius:10px;padding:.6rem;font-size:.9rem;font-weight:600;cursor:pointer;
        transition:all .2s;
      }
      .notif-btn-close:hover { background:rgba(255,255,255,.12); }
    </style>

    <div class='users-container'>
      <div class='page-header'>
        <h1><i class='bi bi-people-fill me-2'></i>User Management</h1>
        <p>Create, activate, deactivate and manage admin accounts</p>
      </div>

      <div class='container'>

        <!-- Users table -->
        <div class='card-5am'>
          <h5><i class='bi bi-list-ul me-2'></i>Active Users</h5>
          <div class='table-responsive'>
            <table class='users-table'>
              <thead><tr>
                <th>#</th><th>Email (Logon)</th><th>Role</th><th>Status</th><th>Actions</th>
              </tr></thead>
              <tbody>";

    foreach ($_users as $_u) {
        $_uid      = (int)$_u['d_user'];
        $_uLogon   = htmlspecialchars($_u['d_logon']);
        $_uRol     = (int)$_u['d_rol'];
        $_isSelf   = ($_uid === (int)$_SESSION['user_id']);
        $_isActive = ($_uRol === 1);
        $_statusBadge = $_isActive
            ? "<span class='badge-active'><i class='bi bi-check-circle me-1'></i>Active</span>"
            : "<span class='badge-inactive'><i class='bi bi-x-circle me-1'></i>Inactive</span>";

        $_roleLabel = $_uRol === 1 ? 'Admin' : 'No access';
        $_selfBadge = $_isSelf ? " <span style='color:#26e3ff;font-size:.72rem;font-weight:700;'>(you)</span>" : "";

        $_toggleBtn = '';
        if (!$_isSelf) {
            if ($_isActive) {
                $_toggleBtn = "<form method='post' style='display:inline;'>
                  <input type='hidden' name='action' value='toggle'>
                  <input type='hidden' name='uid' value='$_uid'>
                  <input type='hidden' name='new_rol' value='0'>
                  <button class='btn-5am btn-deactivate' onclick=\"return confirm('Deactivate $_uLogon?')\">
                    <i class='bi bi-pause-circle me-1'></i>Deactivate
                  </button></form>";
            } else {
                $_toggleBtn = "<form method='post' style='display:inline;'>
                  <input type='hidden' name='action' value='toggle'>
                  <input type='hidden' name='uid' value='$_uid'>
                  <input type='hidden' name='new_rol' value='1'>
                  <button class='btn-5am btn-activate'>
                    <i class='bi bi-play-circle me-1'></i>Activate
                  </button></form>";
            }
        }

        $_resetBtn = "<form method='post' style='display:inline;margin-left:.3rem;'>
          <input type='hidden' name='action' value='reset_pwd'>
          <input type='hidden' name='uid' value='$_uid'>
          <button class='btn-5am btn-reset-pwd' onclick=\"return confirm('Reset password for $_uLogon?')\">
            <i class='bi bi-key me-1'></i>Reset pwd
          </button></form>";

        $_deleteBtn = '';
        if (!$_isSelf) {
            $_deleteBtn = "<form method='post' style='display:inline;margin-left:.3rem;'>
              <input type='hidden' name='action' value='delete'>
              <input type='hidden' name='uid' value='$_uid'>
              <button class='btn-5am btn-delete' onclick=\"return confirm('DELETE $_uLogon? This cannot be undone.')\">
                <i class='bi bi-trash me-1'></i>Delete
              </button></form>";
        }

        $_inhoud .= "<tr>
          <td>$_uid</td>
          <td>$_uLogon$_selfBadge</td>
          <td>$_roleLabel</td>
          <td>$_statusBadge</td>
          <td>$_toggleBtn$_resetBtn$_deleteBtn</td>
        </tr>";
    }

    $_inhoud .= "
              </tbody>
            </table>
          </div>
        </div>

        <!-- Create new user -->
        <div class='card-5am'>
          <h5><i class='bi bi-person-plus-fill me-2'></i>Create New Admin Account</h5>
          <form method='post'>
            <input type='hidden' name='action' value='create'>
            <div class='row g-3'>
              <div class='col-md-7'>
                <label class='lbl'>Email address (used as login)</label>
                <input type='email' name='logon' class='form-ctrl-5am' placeholder='newadmin@email.com' required>
              </div>
              <div class='col-md-3'>
                <label class='lbl'>Role</label>
                <select name='rol' class='form-ctrl-5am'>
                  <option value='1'>Admin (active)</option>
                  <option value='0'>Inactive</option>
                </select>
              </div>
              <div class='col-md-2 d-flex align-items-end'>
                <button type='submit' class='btn-create w-100'><i class='bi bi-plus-circle me-1'></i>Create</button>
              </div>
            </div>
            <p style='color:rgba(255,255,255,.4);font-size:.78rem;margin-top:.6rem;margin-bottom:0;'>
              A random temporary password will be generated and sent to the new user by email.
            </p>
          </form>
        </div>

      </div>
    </div>";

    // ── Notification modal ──────────────────────────────────────────────────
    if ($_modal) {
        $_type  = htmlspecialchars($_modal['type']  ?? 'info');
        $_icon  = htmlspecialchars($_modal['icon']  ?? 'bi-info-circle-fill');
        $_title = $_modal['title'] ?? '';
        $_body  = $_modal['body']  ?? '';
        $_extra = $_modal['extra'] ?? '';

        $_inhoud .= "
    <div class='notif-backdrop' id='notifBackdrop' onclick='closeNotif(event)'>
      <div class='notif-modal' role='dialog' aria-modal='true'>
        <button class='notif-close' onclick='closeNotif()' title='Close'>&times;</button>
        <div class='notif-icon-wrap $_type'>
          <i class='bi $_icon'></i>
        </div>
        <div class='notif-title'>$_title</div>
        <div class='notif-body'>$_body</div>" .
        ($_extra ? "<div class='notif-extra'>$_extra</div>" : "") . "
        <button class='notif-btn-close' onclick='closeNotif()'>
          <i class='bi bi-check2 me-1'></i>OK, Got it
        </button>
      </div>
    </div>
    <script>
      function closeNotif(e) {
        if (e && e.target !== document.getElementById('notifBackdrop')) return;
        const el = document.getElementById('notifBackdrop');
        if (el) { el.style.animation='fadeInBd .15s ease reverse'; setTimeout(()=>el.remove(),140); }
      }
      document.addEventListener('keydown', e => { if(e.key==='Escape') closeNotif(); });
    </script>";
    }

    require("../code/output_admin.inc.php");

} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
