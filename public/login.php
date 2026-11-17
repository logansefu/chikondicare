<?php
require __DIR__.'/_init.php';
use App\Core\Auth; use App\Core\CSRF;
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    CSRF::verify();
    if (Auth::attempt(trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) { header('Location: /dashboard.php'); exit; }
    $error='Login failed. Check your credentials or wait if the account is temporarily locked.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sign In &mdash; ChikondiCare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<div class="cc-african-strip"></div>
<div class="cc-auth-wrapper">
  <div class="cc-auth-card row g-0">
    <div class="col-lg-6 cc-auth-side d-none d-lg-flex">
      <div>
        <div class="cc-hero-pill">
          <i class="bi bi-heart-fill"></i> Chikondi &bull; Care With Love
        </div>
        <h2 class="display-6 fw-bold mb-3">Spreading Health &amp; Wellbeing Across Every Community</h2>
        <p class="text-white-50 mb-4">
          Streamlined patient registration, doctor scheduling, daily check-ins, and consultation care built for modern Malawian clinics.
        </p>
      </div>
      <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.08); border-left: 3px solid var(--cc-amber);">
        <div class="fw-semibold text-white mb-1"><i class="bi bi-shield-check text-warning me-1"></i> Secure Clinical Portal</div>
        <div class="small text-white-50">Role-based access control and audit logging protect every patient record.</div>
      </div>
    </div>
    <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center">
      <div class="mb-4">
        <a class="cc-brand mb-2" href="/login.php">
          <span class="cc-brand-icon"><i class="bi bi-house-heart-fill"></i></span>
          <span>ChikondiCare<span class="cc-brand-sub">Staff Portal</span></span>
        </a>
        <p class="text-muted mb-0 mt-2">Welcome back. Sign in with your staff credentials to continue.</p>
      </div>

      <?php if($error): ?>
        <div class="alert alert-danger rounded-4"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <?= CSRF::field() ?>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" name="username" placeholder="Enter your username" required autofocus>
        </div>
        <div class="mb-4">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
        </div>
        <button class="btn btn-primary w-100 py-2">
          <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Clinic
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>