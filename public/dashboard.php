<?php
require __DIR__.'/_init.php';
use App\Core\Auth; use App\Core\Database;
Auth::requireLogin(); $db=Database::connection(); $u=Auth::user();
$counts=[];
foreach(['patients','doctors','appointments','payments'] as $t){$counts[$t]=(int)$db->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();}
require __DIR__.'/_layout_top.php';
?>
<h2 class="mb-4">Dashboard</h2><div class="row g-3">
<?php foreach(['patients'=>'Registered patients','doctors'=>'Doctors','appointments'=>'Appointments','payments'=>'Payments'] as $k=>$v):?>
<div class="col-md-3"><div class="card p-3"><div class="text-muted"><?=e($v)?></div><div class="display-6"><?=number_format($counts[$k])?></div></div></div><?php endforeach;?>
</div><div class="card p-4 mt-4"><h5>System modules</h5><div class="d-flex flex-wrap gap-2">
<?php $links=[['Patients','/patients/index.php'],['Register patient','/patients/create.php'],['Doctors','/doctors/index.php'],['Appointments','/appointments/index.php'],['Book appointment','/appointments/create.php'],['Today check-in','/checkin/index.php'],['Consultations','/consultations/index.php'],['Payments','/payments/index.php']]; foreach($links as [$label,$url]):?><a class="btn btn-outline-primary" href="<?=e($url)?>"><?=e($label)?></a><?php endforeach;?>
</div><p class="text-muted mt-3 mb-0">Your access to individual modules is checked on the server.</p></div>
<?php require __DIR__.'/_layout_bottom.php'; ?>
