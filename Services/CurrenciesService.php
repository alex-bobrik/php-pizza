<?php

class CurrenciesService
{
    private $bynRate;

    public function __construct()
    {
        $this->setBynRate();
    }

    public function getBynRate()
    {
        return $this->bynRate;
    }

    private function setBynRate()
    {
        // API call BYN rate to USD
        $json = file_get_contents("https://www.nbrb.by/api/exrates/rates/431");
        $obj = json_decode($json);
        $this->bynRate = $obj->Cur_OfficialRate;
    }
}