<?php
require dirname(__DIR__).'/_init.php';
use App\Core\AccessControl; use App\Core\Database;
AccessControl::require('reports.view');
$db = Database::connection();

$patientCount = (int)$db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$doctorCount = (int)$db->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
$totalAppointments = (int)$db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
try { $totalRevenue = (float)$db->query("SELECT COALESCE(SUM(amount),0) FROM payments")->fetchColumn(); }
catch (Throwable $e) { $totalRevenue = 0; }

// Today's appointments - join to get real names like Patients page does
$today = date('Y-m-d');
$todayAppointments = [];
try {
  $q = $db->prepare("SELECT a.*, p.full_name AS patient_name, d.name AS doctor_name FROM appointments a LEFT JOIN patients p ON p.id=a.patient_id LEFT JOIN doctors d ON d.id=a.doctor_id WHERE DATE(a.appointment_date)=? OR DATE(a.created_at)=? ORDER BY a.appointment_date DESC LIMIT 50");
  $q->execute([$today, $today]);
  $todayAppointments = $q->fetchAll();
} catch (Throwable $e) {
  $todayAppointments = $db->query("SELECT * FROM appointments LIMIT 20")->fetchAll();
}
$todayCount = count($todayAppointments);
$allPatients = $db->query("SELECT p.*, d.name AS doctor_name FROM patients p LEFT JOIN doctors d ON d.id=p.primary_doctor_id LIMIT 20")->fetchAll();

require dirname(__DIR__).'/_layout_top.php';
?>

<h2>Reports</h2>
<p class="text-muted">Live reports from your clinic data.</p>

<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="card cc-stat-card cc-stat-mint p-3"><h5>Today's Appointments</h5><h2><?= $todayCount ?></h2><small>Total: <?= $totalAppointments ?></small></div></div>
  <div class="col-md-3"><div class="card cc-stat-card cc-stat-cream p-3"><h5>Total Patients</h5><h2><?= $patientCount ?></h2></div></div>
  <div class="col-md-3"><div class="card cc-stat-card cc-stat-terra p-3"><h5>Total Doctors</h5><h2><?= $doctorCount ?></h2></div></div>
  <div class="col-md-3"><div class="card cc-stat-card cc-stat-mint p-3"><h5>Total Revenue (MK)</h5><h2><?= number_format($totalRevenue) ?></h2></div></div>
</div>

<div class="card p-3 mb-4">
  <h4>Daily Appointment List - Today (<?= date('d M Y') ?>)</h4>
  <table class="table"><thead><tr><th>Patient</th><th>Doctor</th><th>Date / Time</th><th>Status</th></tr></thead><tbody>
  <?php foreach ($todayAppointments as $a): ?>
    <tr><td><?= e($a['patient_name'] ?? $a['patient_id'] ?? '') ?></td><td><?= e($a['doctor_name'] ?? $a['doctor_id'] ?? '') ?></td><td><?= e($a['appointment_date'] ?? $a['created_at'] ?? '') ?></td><td><?= e($a['status'] ?? '') ?></td></tr>
  <?php endforeach; ?>
  <?php if(empty($todayAppointments)): ?><tr><td colspan="4">No appointments today. Total appointments = <?= $totalAppointments ?></td></tr><?php endif; ?>
  </tbody></table>
</div>

<div class="card p-3 mb-4">
  <h4>All Patients (first 20)</h4>
  <table class="table"><thead><tr><th>Patient No.</th><th>Name</th><th>Doctor</th></tr></thead><tbody>
  <?php foreach ($allPatients as $p): ?>
    <tr><td><?= e($p['patient_number'] ?? '') ?></td><td><?= e($p['full_name'] ?? '') ?></td><td><?= e($p['doctor_name'] ?? '') ?></td></tr>
  <?php endforeach; ?>
  </tbody></table>
</div>

<div class="card p-3">
  <h4>Revenue Summary</h4>
  <p>Total collected: <strong>MK <?= number_format($totalRevenue) ?></strong></p>
  <button class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
</div>

<?php require dirname(__DIR__).'/_layout_bottom.php'; ?>