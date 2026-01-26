<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\RDVRepositoryInterface;
use toubilib\core\domain\entities\rdv\RDV;
use GuzzleHttp\Client;

class HttpRDVRepository implements RDVRepositoryInterface {
    private Client $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://api.rdv',
            'timeout'  => 5.0,
        ]);
    }

    public function listerCreneaux($praticienId, $dateDebut, $dateFin): array {
        try {
            $response = $this->client->get("/praticiens/{$praticienId}/rdvs");
            $rdvs = json_decode($response->getBody()->getContents(), true);

            $filtered = [];
            foreach ($rdvs as $rdv) {
                // Ensure array keys exist and filter by date
                if (isset($rdv['date_heure_debut'])) {
                    $start = $rdv['date_heure_debut'];
                    // Basic string comparison for dates YYYY-MM-DD
                    if ($start >= $dateDebut && $start <= $dateFin) {
                        $filtered[] = $rdv;
                    }
                }
            }
            return $filtered;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function creerRendezVous(RDV $rdv): void {
        // Not implemented
    }

    public function getRDVById($id): ?RDV {
        return null;
    }

    public function getRendezVousByPraticien($praticienId, $dateDebut, $dateFin): array {
        return $this->listerCreneaux($praticienId, $dateDebut, $dateFin);
    }

    public function getConsultationsHonorees(string $patientId): array {
        return [];
    }

    public function changerStatusRDV(string $id, int $status): void {
        // Not implemented
    }
}
