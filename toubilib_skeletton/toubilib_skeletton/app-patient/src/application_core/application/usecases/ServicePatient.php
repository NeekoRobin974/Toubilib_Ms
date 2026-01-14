<?php

namespace toubilib\core\application\usecases;


use toubilib\core\application\ports\api\ServicePatientInterface;
use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\domain\entities\patient\Patient;

class ServicePatient implements ServicePatientInterface{
    private PatientRepositoryInterface $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository){
        $this->patientRepository = $patientRepository;
    }

    public function listerPatients(): array{
        return $this->patientRepository->listerPatients();
    }
    public function detailsPatient(string $id): ?Patient{
        return $this->patientRepository->detailsPatient($id);
    }
}