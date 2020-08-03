<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Block;
use App\Repository\BlockRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends AbstractController
{
    /**
     * @return Response
     * @throws Exception
     * @Route ("data/index")
     */
    public function dataIndex()
    {
        $repository = $this->getDoctrine()->getRepository(Block::class);
        /** @var BlockRepository $repository */
        $data = $repository->getIndexData();

        $response = (object)[
            'data' => ($data),
        ];

        $response = json_encode($response);

        echo "<pre>";
        print_r($response);
        echo "</pre>";
        die;

        echo 'asd';
        die;
        return new Response('');
    }
}
