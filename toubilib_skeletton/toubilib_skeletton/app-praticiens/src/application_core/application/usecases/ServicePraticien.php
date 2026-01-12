<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;

class ServicePraticien implements ServicePraticienInterface{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository){
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array{
    	return $this->praticienRepository->listerPraticiens();
    }

    public function afficherPraticien($id): Praticien{
        return $this->praticienRepository->detailsPraticien($id);
    }
}