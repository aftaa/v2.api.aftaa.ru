<?php


namespace App\Controller;


use App\Entity\Block;
use App\Service\CorsPolicy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiBlockController extends AbstractController
{
    /**
     * @Route("/blocks")
     * @return JsonResponse
     */
    public function blocksList(): JsonResponse
    {
        (new CorsPolicy(['https://aftaa.ru']))->sendHeaders();

        $blocks = $this->getDoctrine()->getRepository(Block::class)->getSelectList();

        return $this->json($blocks);
    }
}