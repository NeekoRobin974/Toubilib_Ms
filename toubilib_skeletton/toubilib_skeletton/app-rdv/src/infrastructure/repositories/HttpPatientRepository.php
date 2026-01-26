<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\domain\entities\patient\Patient;
use GuzzleHttp\Client;

class HttpPatientRepository implements PatientRepositoryInterface {
    private Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function listerPatients(): array {
        return [];
    }

    public function detailsPatient(string $id): ?Patient {
        try {
            $response = $this->client->get("/patients/{$id}");
            $data = json_decode($response->getBody()->getContents(), true);

            return new Patient(
                $data['id'],
                $data['nom'],
                $data['prenom'],
                $data['date_naissance'] ?? '',
                $data['adresse'] ?? '',
                $data['code_postal'] ?? '',
                $data['ville'] ?? '',
                $data['email'] ?? '',
                $data['telephone'] ?? ''
            );
        } catch (\Throwable $e) {
            return null;
        }
    }
}
