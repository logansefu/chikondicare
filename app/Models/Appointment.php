<?php
namespace App\Models;
use App\Core\Database;
use App\Exceptions\SlotUnavailableException;
class Appointment {
    public static function book(array $d): int {
        $db=Database::connection();
        try {
            $db->beginTransaction();
            $q=$db->prepare("SELECT id FROM appointments WHERE doctor_id=? AND appointment_date=? AND appointment_time=? AND status IN ('booked','checked_in') FOR UPDATE");
            $q->execute([$d['doctor_id'],$d['appointment_date'],$d['appointment_time']]);
            if ($q->fetch()) throw new SlotUnavailableException('That doctor already has an appointment at this time.');
            $q=$db->prepare("INSERT INTO appointments(patient_id,doctor_id,appointment_date,appointment_time,status,booked_by) VALUES(?,?,?,?,'booked',?)");
            $q->execute([$d['patient_id'],$d['doctor_id'],$d['appointment_date'],$d['appointment_time'],$_SESSION['user']['id']]);
            $id=(int)$db->lastInsertId(); $db->commit(); return $id;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            throw $e;
        }
    }
}
