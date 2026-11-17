<?php
namespace App\Models;
class FullTimeDoctor extends Doctor {
    public function employmentType(): string { return 'full_time'; }
}
