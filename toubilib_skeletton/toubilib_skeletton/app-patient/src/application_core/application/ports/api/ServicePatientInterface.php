<?php
namespace toubilib\core\application\ports\api;

use toubilib\core\domain\entities\patient\Patient;

Interface ServicePatientInterface{
    public function listerPatients(): array;
    public function detailsPatient(string $id): ?Patient;
}