<?php
namespace toubilib\core\application\ports\api\dtos;

use toubilib\core\domain\entities\praticien\Praticien;

class PraticienDTO{
    private Praticien $newPraticien;

    public function __construct(Praticien $newPraticien) {
        $this->newPraticien = $newPraticien;
    }

    public function getNewPraticien(): Praticien {
        return $this->newPraticien;
    }
}