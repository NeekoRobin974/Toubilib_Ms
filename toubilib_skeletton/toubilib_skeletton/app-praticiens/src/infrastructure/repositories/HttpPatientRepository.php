<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\domain\entities\patient\Patient;
use GuzzleHttp\Client;

class HttpPatientRepository implements PatientRepositoryInterface {
    private Client $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://api.patients',
            'timeout'  => 5.0,
        ]);
    }

    public function listerPatients(): array {
        //pas dans ce micro service
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
