<?php

namespace toubilib\infra\repositories;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\Specialite;

class HttpPraticienRepository implements PraticienRepositoryInterface {
    private ClientInterface $client;

    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    public function listerPraticiens(?string $specialite = null, ?string $ville = null): array {
        try {
            $query = [];
            if ($specialite) {
                $query['specialite'] = $specialite;
            }
            if ($ville) {
                $query['ville'] = $ville;
            }

            $response = $this->client->request('GET', '/praticiens', [
                'query' => $query
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $praticiens = [];
            foreach ($data as $pArgs) {
                $specData = $pArgs['specialite'];
                $specialiteObj = new Specialite($specData['id'], $specData['libelle'], $specData['description']);

                $praticiens[] = new Praticien(
                    $pArgs['id'],
                    $pArgs['nom'],
                    $pArgs['prenom'],
                    $specialiteObj
                );
            }
            return $praticiens;

        } catch (GuzzleException $e) {
            //tableau vide en cas d'erreur
            return [];
        }
    }

    public function detailsPraticien(string $id): ?Praticien{
        try {
            $response = $this->client->request('GET', "/praticiens/$id");
            $data = json_decode($response->getBody()->getContents(), true);

            $specData = $data['specialite'];
            $specialiteObj = new Specialite($specData['id'], $specData['libelle'], $specData['description']);

            //il manque moyen paiement et motif visite

            return new Praticien(
                $data['id'],
                $data['nom'],
                $data['prenom'],
                $specialiteObj,
                $data['motifsVisite'] ?? [],
                $data['moyensPaiement'] ?? []
            );
        } catch (GuzzleException $e) {
            return null;
        }
    }
}