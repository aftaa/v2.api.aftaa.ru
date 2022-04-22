<?php


namespace App\Service;


class CorsPolicy
{
    public array $allowedSites;

    /**
     * CorsPolicy constructor.
     * @param array $allowedSites
     */
    public function __construct(array $allowedSites = [])
    {
        $this->allowedSites = $allowedSites;
    }

    public function sendHeaders()
    {
//        if (!empty($_SERVER['HTTP_ORIGIN'])) {
//            $origin = $_SERVER['HTTP_ORIGIN'];
//            if (in_array($origin, $this->allowedSites)) {
//                header("Access-Control-Allow-Origin: $origin");
//            }
//        }
//        header("Access-Control-Allow-Origin: http://gabidullin.online");
//	header("Access-Control-Allow-Origin: https://gabidullin.online");
	header("Access-Control-Allow-Origin: *");
    }

    /**
     * @return array
     */
    public function getAllowedSites(): array
    {
        return $this->allowedSites;
    }

    /**
     * @param array $allowedSites
     * @return CorsPolicy
     */
    public function setAllowedSites(array $allowedSites): CorsPolicy
    {
        $this->allowedSites = $allowedSites;
        return $this;
    }
}
