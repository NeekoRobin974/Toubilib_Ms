<?php

namespace toubilib\core\domain\entities\praticien;

class MoyenPaiement{
    private int $id;
    private string $libelle;

    public function __construct(int $id, string $libelle){
        $this->id = $id;
        $this->libelle = $libelle;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
        ];
    }
}