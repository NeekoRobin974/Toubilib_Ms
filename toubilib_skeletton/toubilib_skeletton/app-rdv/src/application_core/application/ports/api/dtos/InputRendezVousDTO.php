<?php
namespace toubilib\core\application\ports\api\dtos;

class InputRendezVousDTO{
    public string $praticienId;
    public string $patientId;
    public string $dateHeureDebut;
    public string $dateHeureFin;
    public string $motif;
    public int $dureeMinutes;

    public function __construct(string $praticienId, string $patientId, string $dateHeureDebut, string $dateHeureFin, string $motif, int $dureeMinutes){
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->dateHeureDebut = $dateHeureDebut;
        $this->dateHeureFin = $dateHeureFin;
        $this->motif = $motif;
        $this->dureeMinutes = $dureeMinutes;
    }
}