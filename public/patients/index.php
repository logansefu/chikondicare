<?php
require dirname(__DIR__).'/_init.php';
use App\Core\AccessControl; use App\Core\Database;
AccessControl::require('patients.view'); $db=Database::connection();
$term=trim($_GET['q']??'');$page=max(1,(int)($_GET['page']??1));$per=10;$offset=($page-1)*$per;
$sql="SELECT p.*,d.name doctor_name FROM patients p LEFT JOIN doctors d ON d.id=p.primary_doctor_id WHERE p.patient_number LIKE ? OR p.full_name LIKE ? ORDER BY p.id DESC LIMIT $per OFFSET $offset";
$q=$db->prepare($sql);$q->execute(["%$term%","%$term%"]);$rows=$q->fetchAll();
require dirname(__DIR__).'/_layout_top.php';
?>
<div class="d-flex justify-content-between mb-3"><h2>Patients</h2><a class="btn btn-primary" href="/patients/create.php">Register patient</a></div>
<form class="mb-3"><div class="input-group"><input class="form-control" name="q" value="<?=e($term)?>" placeholder="Search patient number or name"><button class="btn btn-outline-secondary">Search</button></div></form>
<div class="table-responsive card p-3"><table class="table table-hover"><thead><tr><th>Patient number</th><th>Name</th><th>Date of birth</th><th>Telephone</th><th>Primary doctor</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=e($r['patient_number'])?></td><td><?=e($r['full_name'])?></td><td><?=e($r['date_of_birth'])?></td><td><?=e($r['telephone'])?></td><td><?=e($r['doctor_name']??'Not assigned')?></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="5">No patients found.</td></tr><?php endif;?></tbody></table></div>
<?php require dirname(__DIR__).'/_layout_bottom.php';?>
