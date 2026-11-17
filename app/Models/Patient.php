<?php
namespace App\Models;
use App\Core\Database;
class Patient {
    public static function create(array $d): string {
        $db=Database::connection();
        $number='CC'.date('ym').str_pad((string)random_int(1,999999),6,'0',STR_PAD_LEFT);
        $q=$db->prepare("INSERT INTO patients(patient_number,full_name,date_of_birth,gender,address,telephone,next_of_kin,primary_doctor_id) VALUES(?,?,?,?,?,?,?,?)");
        $q->execute([$number,$d['full_name'],$d['date_of_birth'],$d['gender'],$d['address'],$d['telephone'],$d['next_of_kin'] ?: null,$d['primary_doctor_id'] ?: null]);
        return $number;
    }
}
