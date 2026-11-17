<?php
use App\Core\Auth;
$user = Auth::user();
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$initials = 'CC';
if (!empty($user['name'])) {
    $parts = preg_split('/\s+/', trim($user['name']));
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
$navItems = [
    ['Dashboard', '/dashboard.php', 'bi-grid-1x2-fill', '/dashboard.php'],
    ['Patients', '/patients/index.php', 'bi-people-fill', '/patients/'],
    ['Doctors', '/doctors/index.php', 'bi-heart-pulse-fill', '/doctors/'],
    ['Appointments', '/appointments/index.php', 'bi-calendar2-check-fill', '/appointments/'],
    ['Check-In', '/checkin/index.php', 'bi-clipboard2-check-fill', '/checkin/'],
    ['Consultations', '/consultations/index.php', 'bi-stethoscope', '/consultations/'],
    ['Payments', '/payments/index.php', 'bi-wallet2', '/payments/'],
    ['Reports', '/reports/index.php', 'bi-file-earmark-bar-graph-fill', '/reports/'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($_ENV['APP_NAME'] ?? 'ChikondiCare') ?> — Community Health &amp; Care</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<div class="cc-african-strip"></div>
<nav class="navbar navbar-expand-xl cc-navbar">
  <div class="container-fluid px-lg-4">
    <a class="cc-brand" href="/dashboard.php">
      <span class="cc-brand-icon"><i class="bi bi-house-heart-fill"></i></span>
      <span>
        ChikondiCare
        <span class="cc-brand-sub">Compassionate Clinic</span>
      </span>
    </a>

    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#ccMainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="ccMainNav">
      <ul class="navbar-nav mx-auto cc-nav-pills gap-1 my-2 my-xl-0">
        <?php foreach ($navItems as [$label, $href, $icon, $prefix]):
          $active = str_starts_with($currentUri, $prefix) ? 'active' : '';
        ?>
          <li class="nav-item">
            <a class="nav-link <?= $active ?>" href="<?= e($href) ?>">
              <i class="bi <?= e($icon) ?>"></i> <?= e($label) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="d-flex align-items-center gap-2 mt-2 mt-xl-0">
        <div class="cc-user-chip">
          <span class="cc-user-avatar"><?= e($initials) ?></span>
          <span class="fw-semibold text-dark"><?= e($user['name'] ?? '') ?></span>
          <span class="cc-role-tag"><?= e($user['role'] ?? '') ?></span>
        </div>
        <a class="btn btn-sm btn-outline-primary" href="/logout.php" title="Sign out">
          <i class="bi bi-box-arrow-right me-1"></i>Logout
        </a>
      </div>
    </div>
  </div>
</nav>
<main class="container py-4">