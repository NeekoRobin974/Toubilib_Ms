<?php
namespace toubilib\core\application\ports\api\dtos;

use toubilib\core\domain\entities\patient\Patient;

class PatientDTO{
    private Patient $newPatient;

    public function __construct(Patient $newPatient) {
        $this->newPatient = $newPatient;
    }

    public function getNewPatient(): Patient {
        return $this->newPatient;
    }
}