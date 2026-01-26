<?php
namespace toubilib\core\application\ports\spi;

use toubilib\core\domain\entities\patient\Patient;

Interface PatientRepositoryInterface{
    public function listerPatients(): array;
    public function detailsPatient(string $id): ?Patient;
}