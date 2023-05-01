<?php


namespace App\Controller;


use App\Service\Cors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        (new Cors(['https://aftaa.ru']))->sendHeaders();
        parent::__construct();
    }
}
