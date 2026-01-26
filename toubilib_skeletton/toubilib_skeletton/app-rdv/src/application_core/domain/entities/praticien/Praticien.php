<?php

namespace toubilib\core\domain\entities\praticien;

class Praticien{
    private string $id;
    private string $nom;
    private string $prenom;
    private Specialite $specialite;
    private array $motifsVisite;
    private array $moyensPaiement;

    public function __construct(string $id, string $nom, string $prenom, Specialite $specialite, array $motifsVisite = [], array $moyensPaiement = []){
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->specialite = $specialite;
        $this->motifsVisite = $motifsVisite;
        $this->moyensPaiement = $moyensPaiement;
    }

    public function getSpecialite(): Specialite{
        return $this->specialite;
    }

    public function getID(): string{
        return $this->id;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'specialite' => $this->specialite ->toArray(),
            'motifsVisite' => array_map(fn($m) => $m->toArray(), $this->motifsVisite),
            'moyensPaiement' => array_map(fn($m) => $m->toArray(), $this->moyensPaiement),
        ];
    }

    public function getMotifsAutorises(): array{
        return $this->motifsVisite;
    }
}