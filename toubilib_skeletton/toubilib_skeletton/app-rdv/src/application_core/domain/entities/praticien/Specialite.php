<?php

namespace toubilib\core\domain\entities\praticien;


class Specialite{
    private string $id;
    private string $libelle;
    private ?string $description;

    public function __construct(string $id, string $libelle, ?string $description = null){
        $this->id = $id;
        $this->libelle = $libelle;
        $this->description = $description;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'description' => $this->description,
        ];
    }
}