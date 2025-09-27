<?php
/* Smarty version 3.1.31, created on 2025-04-21 09:50:02
  from "D:\Alexis Code Projects\The5AM-Website\1-Full-Site\public_html\smarty\templates\logon.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_680614cab55ff8_37819260',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '96733302a2d99a140f6d1c8cafc68df8760c16da' => 
    array (
      0 => 'D:\\Alexis Code Projects\\The5AM-Website\\1-Full-Site\\public_html\\smarty\\templates\\logon.tpl',
      1 => 1745228996,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_680614cab55ff8_37819260 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../scss/style_admin.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <?php echo '<script'; ?>
 src="../js/jquery-3.6.0.min.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="../js/bootstrap.min.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="../js_lib/copyright.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="https://kit.fontawesome.com/f74746c02f.js" crossorigin="anonymous"><?php echo '</script'; ?>
>

  
  <?php echo '<script'; ?>
>
    $(document).ready(function() {
      $("#toggle_pwd").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password");
        if (input.attr("type") === "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
    });
  <?php echo '</script'; ?>
>

  <title>The5AM Log-in</title>
</head>

<body>
  <div class="container-fluid px-0">
    <div class="row mx-0">
      <div class="col-md-6 col-lg-8 ps-0 d-flex justify-content-center">
        <img src="../img/5am_Logo_01-01.png" class="img-fluid" style="width: 500px;" alt="Logo">
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="container">
          <p id="msg"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p>
          <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" class="form-horizontal">
            <div class="form-group">
              <label for="username" class="form-label">User</label>
              <input type="text" name="logon" class="form-control" id="username" placeholder="user name">
            </div>
            <div class="form-group position-relative">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="paswoord" class="form-control" id="password" placeholder="password">
              <span class="position-absolute" style="right: 0px; top: 5px;">
                <i class="fa fa-eye field_icon" id="toggle_pwd"></i></span>
            </div>
            <div class="form-check">
              <label class="form-check-label">Permanent<br>(8 hours)</label>
              <input type="checkbox" class="form-check-input" name="persist">
            </div>
            <input type="submit" name="submit" value="start session" class="btn1 signIn w-100 py-3 my-2 text-white">
            <h2><a href="/scripts/A_home.php" style="text-decoration: none;">
                < Back to site</a>
            </h2>
            <div class="col-6">
              <div class="mb-3">
                <a href="../scripts/P_vergeten.php" class="forgotPass">Forgot password?</a>
              </div>
            </div>
            <div class="">
              <p>© 2025 - The 5 AM</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html><?php }
}
