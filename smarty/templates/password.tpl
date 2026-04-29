<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blind Monkey - {$pagetitle}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Public Sans', sans-serif;
      background: #0a0a0a;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
    .password-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 20px;
      padding: 2.5rem;
      width: 100%;
      max-width: 480px;
      backdrop-filter: blur(15px);
    }
    .brand-logo { height: 60px; margin-bottom: 1.5rem; border-radius: 8px; }
    h2 { color: #fff; font-weight: 700; font-size: 1.6rem; margin-bottom: 0.25rem; }
    .subtitle { color: rgba(255,255,255,0.5); font-size: 0.9rem; margin-bottom: 2rem; }
    .form-label { color: rgba(255,255,255,0.8); font-size: 0.9rem; display: block; margin-bottom: .35rem; }
    .form-control {
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.15);
      color: #fff;
      border-radius: 10px;
      padding: 0.75rem 1rem;
      width: 100%;
      font-size: .95rem;
    }
    .form-control:focus {
      outline: none;
      background: rgba(255,255,255,0.1);
      border-color: #26e3ff;
      color: #fff;
      box-shadow: 0 0 0 3px rgba(38,227,255,0.15);
    }
    .form-control::placeholder { color: rgba(255,255,255,0.3); }
    .mb-3 { margin-bottom: 1rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .mt-2 { margin-top: .5rem; }
    .btn-primary-5am {
      background: linear-gradient(135deg, #26e3ff 0%, #1a9fb8 100%);
      color: #000;
      border: none;
      border-radius: 10px;
      padding: 0.75rem 2rem;
      font-weight: 700;
      font-size: 1rem;
      width: 100%;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .btn-primary-5am:hover {
      filter: brightness(1.1);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(38,227,255,0.4);
    }
    .btn-primary-5am:disabled { opacity: .4; cursor: not-allowed; transform: none; }
    .msg-success {
      background: rgba(16,185,129,0.15);
      border: 1px solid rgba(16,185,129,0.4);
      color: #10b981;
      border-radius: 10px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
    }
    .msg-error {
      background: rgba(239,68,68,0.15);
      border: 1px solid rgba(239,68,68,0.4);
      color: #ef4444;
      border-radius: 10px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
    }
    .back-link {
      color: rgba(255,255,255,0.5);
      font-size: 0.85rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      margin-top: 1.5rem;
      transition: color 0.2s;
    }
    .back-link:hover { color: #26e3ff; }
    .password-strength {
      height: 4px; border-radius: 2px;
      margin-top: 0.4rem; transition: all 0.3s; width: 0;
    }
    .strength-text { font-size: 0.78rem; margin-top: 0.3rem; min-height: 1.1em; }
    .match-msg    { font-size: 0.8rem;  margin-top: 0.3rem; min-height: 1.1em; }
  </style>
</head>

<body>
  <div class="password-card">
    <div class="text-center" style="text-align:center;">
      <img src="../img/blind_monkey_logo.jpg" class="brand-logo" alt="Blind Monkey">
      <h2>{$pagetitle}</h2>
      <p class="subtitle">{$pagesubtitle}</p>
    </div>
    {$inhoud}
    <div style="text-align:center;">
      <a href="../scripts/A_logon.php" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to login
      </a>
    </div>
  </div>

  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  {if $extrajs}
  <script>
    {$extrajs}
  </script>
  {/if}
</body>
</html>
