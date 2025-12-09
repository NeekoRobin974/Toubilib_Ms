<?php
namespace toubilib\infra\repositories;

use PDO;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;
use toubilib\core\domain\entities\rdv\RDV;

class PDORdvRepository implements RDVRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listerCreneaux($praticienId, $dateDebut, $dateFin): array
    {
        $sql = "SELECT date_heure_debut, date_heure_fin 
            FROM rdv
            WHERE praticien_id = :praticienId
              AND date_heure_debut >= :dateDebut
              AND date_heure_fin <= :dateFin";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'praticienId' => $praticienId,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function creerRendezVous(RDV $rdv): void
    {
        $sql = "INSERT INTO rdv (praticien_id, patient_id, date_heure, motif, duree_minutes)
                VALUES ($rdv->getIdPrat(), $rdv->getIdPat(), $rdv->getDateHeureDebut(),$rdv->getDateHeureFin, $rdv->getMotif(), $rdv->getDuree())";
    }

    public function getRDVById($id): ?RDV{
        $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new \toubilib\core\domain\entities\rdv\RDV(
            $row['praticien_id'],
            $row['patient_id'],
            $row['date_heure_debut'],
            $row['date_heure_fin'],
            $row['motif_visite'],
            $row['duree'],
            $row['status'] ?? 0
        );
    }

    public function getRendezVousByPraticien($praticienId, $dateDebut = null, $dateFin = null): array
    {
        {
            $sql = "SELECT id, date_heure_debut,date_heure_fin, duree, motif_visite, status, patient_id
            FROM rdv
            WHERE praticien_id = :praticienId";
            $params = ['praticienId' => $praticienId];

            if ($dateDebut) {
                $sql .= " AND DATE(date_heure_debut) >= :dateDebut";
                $params['dateDebut'] = $dateDebut;
            }
            if ($dateFin) {
                $sql .= " AND DATE(date_heure_fin) <= :dateFin";
                $params['dateFin'] = $dateFin;
            }
            $sql .= " ORDER BY date_heure_debut ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rdvs as &$rdv) {
                $rdv['details du patient'] = "http://localhost:6080/patients/" . $rdv['patient_id'];
            }

            return $rdvs;
        }
    }

    public function annulerRendezVous($id): void{
        $sql = "UPDATE rdv SET status = 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function changerStatusRDV(string $id, int $status): void {
        $stmt = $this->pdo->prepare('UPDATE rdv SET status = :status WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
}