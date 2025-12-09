<?php
namespace toubilib\core\application\ports\api;

use toubilib\core\application\ports\api\dtos\InputRendezVousDTO;
use toubilib\core\domain\entities\rdv\RDV;

Interface ServiceRDVInterface{
    public function listerCreneaux($praticienId, $dateDebut, $dateFin): array;
    public function getRDVById($id): ?RDV;
    public function creerRendezVous(InputRendezVousDTO $dto): void;
    public function getAgendaPraticien($praticienId, $dateDebut = null, $dateFin = null): array;
    public function annulerRendezVous($id): void;
    public function getHistoriqueConsultations(string $patientId): array;


}