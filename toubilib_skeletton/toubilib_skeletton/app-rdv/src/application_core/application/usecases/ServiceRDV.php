<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\api\dtos\InputRendezVousDTO;
use toubilib\core\application\ports\api\ServiceRDVInterface;
use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;
use toubilib\core\domain\entities\rdv\RDV;

class ServiceRDV implements ServiceRDVInterface
{
    private RDVRepositoryInterface $rdvRepository;
    private PraticienRepositoryInterface $praticienRepository;
    private PatientRepositoryInterface $patientRepository;

    public function __construct(RDVRepositoryInterface $rdvRepository , PraticienRepositoryInterface $praticienRepository, PatientRepositoryInterface $patientRepository)
    {
        $this->rdvRepository = $rdvRepository;
        $this->praticienRepository = $praticienRepository;
        $this->patientRepository = $patientRepository;
    }

    public function listerCreneaux($praticienId, $dateDebut, $dateFin): array
    {
        return $this->rdvRepository->listerCreneaux($praticienId, $dateDebut, $dateFin);
    }

    public function creerRendezVous(InputRendezVousDTO $dto): void
    {
        //Verifier l'existance du praticien et du patient
        if(!in_array($dto->praticienId, $this->praticienRepository->listerPraticiens())){
            throw new \Exception("Praticien inexistant");
        }

        if(!in_array($dto->patientId, $this->patientRepository->listerPatients())){
            throw new \Exception("Patient inexistant");
        }

        //Verifier la validité du motif
        if (!in_array($dto->motif, $this->praticienRepository->detailsPraticien($dto->praticienId)->getMotifsAutorises())) {
            throw new \Exception("Motif non autorisé pour ce praticien");
        }

        //Verifier la validité du créneau
        $dateDebut = new \DateTimeImmutable($dto->dateHeureDebut);
        $dateFin = new \DateTimeImmutable($dto->dateHeureFin);
        $jour = (int)$dateDebut->format('N');
        $heureDebut = (int)$dateDebut->format('H');
        $heureFin = (int)$dateFin->format('H');
        $minuteFin = (int)$dateFin->format('i');
        if ($jour > 5 || $heureDebut < 8 || $heureFin > 19 || ($heureFin == 19 && $minuteFin > 0)) {
            throw new \Exception("Créneau hors horaires ouvrés");
        }

        //Verifier la disponibilité du praticien
        $creneaux = $this->rdvRepository->listerCreneaux($dto->praticienId, $dto->dateHeureDebut, $dto->dateHeureFin);
        foreach ($creneaux as $c) {
            $debut = new \DateTimeImmutable($c['date_heure_debut']);
            $fin = new \DateTimeImmutable($c['date_heure_fin']);
            if ($dateDebut < $fin && $dateFin > $debut) {
                throw new \Exception("Praticien indisponible sur ce créneau");
            }
        }


        $rdv = new RDV(
            $dto->praticienId,
            $dto->patientId,
            $dto->dateHeureDebut,
            $dto->dateHeureFin,
            $dto->motif,
            $dto->dureeMinutes
        );
        $this->rdvRepository->creerRendezVous($rdv);
    }
    public function getRDVById($id): ?RDV{
        return $this->rdvRepository->getRDVById($id);
    }

    public function getAgendaPraticien($praticienId, $dateDebut = null, $dateFin = null): array {
        return $this->rdvRepository->getRendezVousByPraticien($praticienId, $dateDebut, $dateFin);
    }
    public function annulerRendezVous($id): void
    {
        $rdv = $this->rdvRepository->getRDVById($id);
        if ($rdv === null) {
            throw new \DomainException("Rendez-vous inexistant");
        }
        $this->rdvRepository->annulerRendezVous($id);
    }
    public function getHistoriqueConsultations(string $patientId): array
    {
        return $this->rdvRepository->getConsultationsHonorees($patientId);
    }

    public function getRDVParPraticien(string $idPrat): array
    {
        return $this->rdvRepository->getRendezVousByPraticien($idPrat);
    }

}