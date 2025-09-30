<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The5AM - Admin Login</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom CSS - DEBE IR AL FINAL -->
  <link rel="stylesheet" href="../scss/style_logon.css">
</head>

<body>
  <div class="login-wrapper">
    <div class="container-fluid">
      <div class="row min-vh-100 g-0">
        
        <!-- ========== LEFT PANEL - BRANDING ========== -->
        <div class="col-lg-6 left-panel d-flex align-items-center justify-content-center">
          <div class="branding-content text-center">
            
            <!-- Logo -->
            <div class="logo-wrapper mb-4">
              <img src="../img/5am_Logo_01-01.png" class="brand-logo" alt="The 5AM Logo">
            </div>
            
            <!-- Welcome Text - Desktop Only -->
            <div class="welcome-section d-none d-lg-block">
              <h2 class="welcome-title">Welcome Back</h2>
              <p class="welcome-subtitle">Access your admin dashboard</p>
            </div>
            
            <!-- Back to Site - Desktop Only -->
            <div class="back-link-desktop d-none d-lg-block mt-4">
              <a href="../scripts/A_home.php" class="btn btn-outline-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Back to Website
              </a>
            </div>
            
          </div>
        </div>
        
        <!-- ========== RIGHT PANEL - LOGIN FORM ========== -->
        <div class="col-lg-6 right-panel d-flex align-items-center justify-content-center">
          <div class="form-wrapper">
            
            <!-- Mobile Logo - Only visible on mobile -->
            <div class="mobile-logo-section d-lg-none text-center mb-4">
              <img src="../img/5am_Logo_01-01.png" class="mobile-logo" alt="The 5AM Logo">
            </div>
            
            <!-- Form Header -->
            <div class="form-header text-center mb-4">
              <h1 class="form-title">Admin Login</h1>
              <p class="form-subtitle">Sign in to your account</p>
            </div>
            
            <!-- Alert Message -->
            {if $msg && $msg != " "}
              <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span class="alert-text">{$msg}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            {/if}
            
            <!-- Login Form -->
            <form method="post" action="{$action}" class="login-form" autocomplete="on">
              
              <!-- Username Field -->
              <div class="form-group mb-4">
                <label for="username" class="form-label">
                  <i class="bi bi-person-fill me-2"></i>Username
                </label>
                <input 
                  type="text" 
                  name="logon" 
                  class="form-control form-control-lg" 
                  id="username" 
                  placeholder="Enter your username" 
                  autocomplete="username"
                  required>
              </div>
              
              <!-- Password Field -->
              <div class="form-group mb-4">
                <label for="password" class="form-label">
                  <i class="bi bi-lock-fill me-2"></i>Password
                </label>
                <div class="password-wrapper">
                  <input 
                    type="password" 
                    name="paswoord" 
                    class="form-control form-control-lg password-input" 
                    id="password" 
                    placeholder="Enter your password"
                    autocomplete="current-password" 
                    required>
                  <button type="button" class="password-toggle-btn" id="toggle_pwd" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              
              <!-- Remember Me Checkbox -->
              <div class="form-check mb-4 custom-checkbox">
                <input 
                  type="checkbox" 
                  class="form-check-input" 
                  name="persist" 
                  id="remember">
                <label class="form-check-label" for="remember">
                  Keep me signed in for 8 hours
                </label>
              </div>
              
              <!-- Submit Button -->
              <div class="d-grid mb-4">
                <button type="submit" name="submit" class="btn btn-primary btn-lg btn-login">
                  <i class="bi bi-box-arrow-in-right me-2"></i>
                  Sign In
                </button>
              </div>
              
              <!-- Forgot Password Link -->
              <div class="text-center mb-4">
                <a href="../scripts/P_vergeten.php" class="forgot-link">
                  <i class="bi bi-question-circle me-1"></i>
                  Forgot your password?
                </a>
              </div>
              
              <!-- Back to Site - Mobile Only -->
              <div class="text-center d-lg-none mb-3">
                <a href="../scripts/A_home.php" class="btn btn-outline-secondary btn-back-mobile">
                  <i class="bi bi-arrow-left me-2"></i>Back to Website
                </a>
              </div>
              
            </form>
            
            <!-- Footer -->
            <div class="form-footer text-center mt-4">
              <p class="copyright-text">© 2025 The 5 AM. All rights reserved.</p>
            </div>
            
          </div>
        </div>
        
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  
  <!-- Password Toggle Script -->
  <script>
    $(document).ready(function() {
      $("#toggle_pwd").click(function() {
        const icon = $(this).find("i");
        const input = $("#password");
        
        icon.toggleClass("fa-eye fa-eye-slash");
        
        if (input.attr("type") === "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
      });
      
      // Auto-dismiss alerts after 5 seconds
      setTimeout(function() {
        $(".alert").fadeOut("slow");
      }, 5000);
    });
  </script>
</body>

</html>