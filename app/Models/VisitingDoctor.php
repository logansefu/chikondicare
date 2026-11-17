<?php
namespace App\Models;
class VisitingDoctor extends Doctor {
    public function __construct(string $n,string $name,string $tel,string $room,private string $nextVisitDate,private int $visitDuration)
    { parent::__construct($n,$name,$tel,$room); }
    public function employmentType(): string { return 'visiting'; }
    public function details(): array { return parent::details()+['next_visit_date'=>$this->nextVisitDate,'next_visit_duration'=>$this->visitDuration]; }
}
