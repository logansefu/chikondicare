<?php
require dirname(__DIR__).'/_init.php';
use App\Core\AccessControl; use App\Core\Database; use App\Core\CSRF; use App\Models\Patient; use App\Core\Validator; use App\Exceptions\ValidationException;
AccessControl::require('patients.manage');$db=Database::connection();$errors=[];$data=$_POST;
if($_SERVER['REQUEST_METHOD']==='POST'){CSRF::verify();try{
$v=(new Validator())->required($data,'full_name','Full name')->required($data,'date_of_birth','Date of birth')->dateNotFuture($data,'date_of_birth','Date of birth')->required($data,'gender','Gender')->required($data,'address','Address')->required($data,'telephone','Telephone')->phone($data,'telephone','Telephone');$v->validate();
$number=Patient::create($data);header('Location: /patients/index.php?created='.urlencode($number));exit;
}catch(ValidationException $e){$errors=$e->errors;}catch(Throwable $e){error_log($e->getMessage());$errors['_general']='Could not save patient. Check the entered information.';}}
$doctors=$db->query("SELECT id,name FROM doctors WHERE is_active=1 ORDER BY name")->fetchAll();
require dirname(__DIR__).'/_layout_top.php';
?>
<h2>Register patient</h2><?php if(isset($errors['_general'])):?><div class="alert alert-danger"><?=e($errors['_general'])?></div><?php endif;?>
<div class="card p-4"><form method="post"><?=CSRF::field()?><div class="row g-3">
<?php foreach(['full_name'=>'Full name','date_of_birth'=>'Date of birth','address'=>'Residential address','telephone'=>'Telephone','next_of_kin'=>'Next of kin'] as $field=>$label):?><div class="col-md-6"><label class="form-label"><?=e($label)?></label><input class="form-control" name="<?=e($field)?>" value="<?=e($data[$field]??'')?>" <?=in_array($field,['full_name','date_of_birth','address','telephone'])?'required':''?> type="<?= $field==='date_of_birth'?'date':'text' ?>"><?php if(isset($errors[$field])):?><small class="text-danger"><?=e($errors[$field])?></small><?php endif;?></div><?php endforeach;?>
<div class="col-md-6"><label class="form-label">Gender</label><select name="gender" class="form-select" required><option value="">Choose</option><?php foreach(['Male','Female','Other'] as $g):?><option <?=($data['gender']??'')===$g?'selected':''?>><?=e($g)?></option><?php endforeach;?></select></div>
<div class="col-md-6"><label class="form-label">Primary doctor</label><select name="primary_doctor_id" class="form-select"><option value="">Not assigned</option><?php foreach($doctors as $d):?><option value="<?=$d['id']?>"><?=e($d['name'])?></option><?php endforeach;?></select></div>
</div><button class="btn btn-primary mt-4">Save patient</button> <a class="btn btn-secondary mt-4" href="/patients/index.php">Cancel</a></form></div>
<?php require dirname(__DIR__).'/_layout_bottom.php';?>
