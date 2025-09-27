<?php
/* Smarty version 3.1.31, created on 2025-09-21 08:37:56
  from "D:\Alexis Code Projects\The5AM-Website\1-Full-Site\smarty\templates\logon.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_68cfb964418497_18080835',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fd7e86802082657baa84d6fe437e0fa766bfc3e2' => 
    array (
      0 => 'D:\\Alexis Code Projects\\The5AM-Website\\1-Full-Site\\smarty\\templates\\logon.tpl',
      1 => 1758443856,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68cfb964418497_18080835 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
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

  <title>The5AM - Admin Login</title>
</head>

<body class="login-body">
  <div class="login-container">
    <div class="row min-vh-100 g-0">
      
      <!-- Left Panel - Logo and Branding -->
      <div class="col-lg-6 login-left-panel d-flex align-items-center justify-content-center">
        <div class="text-center p-4">
          <div class="logo-container mb-4">
            <img src="../img/5am_Logo_01-01.png" class="logo-img" alt="The 5AM Logo">
          </div>
          
          <!-- Welcome text for larger screens -->
          <div class="welcome-text d-none d-lg-block">
            <h2 class="text-white mb-3">Welcome Back</h2>
            <p class="text-light opacity-75">Access your admin dashboard</p>
          </div>
          
          <!-- Back to site link - visible on desktop -->
          <div class="back-to-site d-none d-lg-block mt-4">
            <a href="/scripts/A_home.php" class="btn btn-outline-light">
              <i class="bi bi-arrow-left me-2"></i>Back to Website
            </a>
          </div>
        </div>
      </div>
      
      <!-- Right Panel - Login Form -->
      <div class="col-lg-6 login-right-panel d-flex align-items-center">
        <div class="login-form-container w-100">
          <div class="login-form-inner">
            
            <!-- Header -->
            <div class="login-header text-center mb-4">
              <h1 class="h3 mb-2">Admin Login</h1>
              <p class="text-muted">Sign in to your account</p>
            </div>
            
            <!-- Message -->
            <?php if ($_smarty_tpl->tpl_vars['msg']->value && $_smarty_tpl->tpl_vars['msg']->value != " ") {?>
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php }?>
            
            <!-- Login Form -->
            <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" class="login-form">
              
              <!-- Username -->
              <div class="form-group mb-3">
                <label for="username" class="form-label">
                  <i class="bi bi-person me-2"></i>Username
                </label>
                <input type="text" name="logon" class="form-control form-control-lg" 
                       id="username" placeholder="Enter your username" required>
              </div>
              
              <!-- Password -->
              <div class="form-group mb-3">
                <label for="password" class="form-label">
                  <i class="bi bi-lock me-2"></i>Password
                </label>
                <div class="password-input-container position-relative">
                  <input type="password" name="paswoord" class="form-control form-control-lg" 
                         id="password" placeholder="Enter your password" required>
                  <button type="button" class="password-toggle" id="toggle_pwd">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>
              
              <!-- Remember Me -->
              <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" name="persist" id="remember">
                <label class="form-check-label" for="remember">
                  Keep me signed in (8 hours)
                </label>
              </div>
              
              <!-- Submit Button -->
              <div class="d-grid mb-3">
                <button type="submit" name="submit" class="btn btn-login btn-lg">
                  <i class="bi bi-box-arrow-in-right me-2"></i>
                  Sign In
                </button>
              </div>
              
              <!-- Forgot Password -->
              <div class="text-center mb-4">
                <a href="../scripts/P_vergeten.php" class="forgot-password-link">
                  <i class="bi bi-question-circle me-1"></i>
                  Forgot your password?
                </a>
              </div>
              
              <!-- Back to site - Mobile -->
              <div class="text-center d-lg-none">
                <a href="/scripts/A_home.php" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left me-2"></i>Back to Website
                </a>
              </div>
              
            </form>
            
            <!-- Footer -->
            <div class="login-footer text-center mt-4">
              <small class="text-muted">© 2025 The 5 AM. All rights reserved.</small>
            </div>
            
          </div>
        </div>
      </div>
      
    </div>
  </div>
</body>

</html><?php }
}
