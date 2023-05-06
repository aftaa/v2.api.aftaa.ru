<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends AbstractController
{
    /**
     * @param mixed $data
     * @return JsonResponse
     */
    public function jsonAndHeader(mixed $data): JsonResponse
    {
        return $this->json(data: $data, headers: [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
