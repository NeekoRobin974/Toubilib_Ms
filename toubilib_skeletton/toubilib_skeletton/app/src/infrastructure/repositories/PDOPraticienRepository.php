<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\MotifVisite;
use toubilib\core\domain\entities\praticien\MoyenPaiement;
use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\Specialite;

class PDOPraticienRepository implements PraticienRepositoryInterface{
    private \PDO $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }

    public function listerPraticiens(?string $specialite = null, ?string $ville = null): array{
        $sql = 'SELECT p.*, s.id as specialite_id, s.libelle as specialite_libelle, s.description as specialite_description FROM praticien p JOIN specialite s ON p.specialite_id = s.id';
        $params = [];
        $conditions = [];
        if ($specialite !== null) {
            $conditions[] = 'LOWER(s.libelle) = LOWER(:specialite)';
            $params['specialite'] = $specialite;
        }
        if ($ville !== null) {
            $conditions[] = 'LOWER(p.ville) = LOWER(:ville)';
            $params['ville'] = $ville;
        }
        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $praticiens = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $specialite = new Specialite(
                (int)$row['specialite_id'],
                $row['specialite_libelle'],
                $row['specialite_description']
            );
            $praticiens[] = new Praticien(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $specialite
            );
        }
        return $praticiens;
    }

    public function detailsPraticien(string $id): ?Praticien{
        $stmt = $this->pdo->prepare('SELECT p.*, s.id as specialite_id, s.libelle as specialite_libelle, s.description as specialite_description FROM praticien p JOIN specialite s ON p.specialite_id = s.id WHERE p.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        //Specialité
        $specialite = new Specialite(
            (int)$row['specialite_id'],
            $row['specialite_libelle'],
            $row['specialite_description']
        );

        //Motif de visite
        $stmtMotifs = $this->pdo->prepare('SELECT m.id, m.libelle FROM praticien2motif pm JOIN motif_visite m ON pm.motif_id = m.id WHERE pm.praticien_id = :id');
        $stmtMotifs->execute(['id' => $id]);
        $motifs = [];
        while ($motif = $stmtMotifs->fetch(\PDO::FETCH_ASSOC)){
            $motifs[] = new MotifVisite($motif['id'], $motif['libelle']);
        }

        //Moyens de paiement
        $stmtMoyens = $this->pdo->prepare('SELECT mp.id, mp.libelle FROM praticien2moyen pm JOIN moyen_paiement mp ON pm.moyen_id = mp.id WHERE pm.praticien_id = :id');
        $stmtMoyens->execute(['id' => $id]);
        $moyens = [];
        while ($moyen = $stmtMoyens->fetch(\PDO::FETCH_ASSOC)){
            $moyens[] = new MoyenPaiement($moyen['id'], $moyen['libelle']);
        }

        //Affichage du praticien
        return new Praticien(
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $specialite,
            $motifs,
            $moyens
        );
    }
}