<?php
/* Smarty version 3.1.31, created on 2023-07-22 00:33:49
  from "/home/a102235/domains/donmateo.ziehoeveelmensenbetaalden1euro.com/public_html/smarty/templates/logon.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_64bb07cdebfbf4_60912616',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ab187c43ad0a064afecd1b4936e220e60bda3d11' => 
    array (
      0 => '/home/a102235/domains/donmateo.ziehoeveelmensenbetaalden1euro.com/public_html/smarty/templates/logon.tpl',
      1 => 1689970199,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_64bb07cdebfbf4_60912616 (Smarty_Internal_Template $_smarty_tpl) {
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
  
  <title>Don Mateo Log-in</title>
</head>

<body>
  <div class="container-fluid px-0">
    <div class="row mx-0">
      <div class="col-md-6 col-lg-8 ps-0 d-flex justify-content-center">
        <img src="/img/logo_dmp.jpg" class="img-fluid" style="width: 500px;" alt="Logo">
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="container">
          <p id="msg"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p>
          <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" class="form-horizontal">
            <div class="form-group">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" name="logon" class="form-control" id="username" placeholder="Nombre de usuario">
            </div>
            <div class="form-group position-relative">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="paswoord" class="form-control" id="password" placeholder="Contraseña">
              <span class="position-absolute" style="right: 0px; top: 5px;">
                <i class="fa fa-eye field_icon" id="toggle_pwd"></i></span>              
            </div>
            <div class="form-check">
              <label class="form-check-label">Permanente<br>(8 horas)</label>
              <input type="checkbox" class="form-check-input" name="persist">
            </div>
            <input type="submit" name="submit" value="iniciar sesión" class="btn1 signIn w-100 py-3 my-2 text-white">
            <h2><a href="/scripts/A_home.php" style="text-decoration: none;"> < Volver al sitio</a> </h2>
            <div class="col-6">
              <div class="mb-3">
                <a href="../scripts/P_vergeten.php" class="forgotPass">Olvidó la contraseña?</a>
              </div>
            </div>
            <div class="">
              <p>© 2023 - Don Mateo</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<?php }
}
