<?php
namespace App\Models;
abstract class Doctor {
    public function __construct(
        protected string $doctorNumber,
        protected string $name,
        protected string $telephone,
        protected string $room
    ) {}
    abstract public function employmentType(): string;
    public function details(): array {
        return ['doctor_number'=>$this->doctorNumber,'name'=>$this->name,'telephone'=>$this->telephone,'room_number'=>$this->room,'employment_type'=>$this->employmentType()];
    }
}
