<?php
namespace toubilib\core\application\ports\api;

use toubilib\core\domain\entities\praticien\Praticien;

Interface ServicePraticienInterface{
    public function listerPraticiens(): array;
    public function afficherPraticien($id): Praticien;
}