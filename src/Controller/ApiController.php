<?php


namespace App\Controller;


use App\Service\CorsPolicy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();
        parent::__construct();
    }
}
