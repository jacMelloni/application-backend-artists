<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    private $repository;

    public function __construct(AlbumRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/albums/{token}", name="get_album", methods={"GET"})
     *
     */
    public function getAlbum($token)
    {
        $album = $this->repository->findOneBy([
            'token' => $token,
        ]);

        if (!$album) {
            return new JsonResponse();
        }

        $response = $album->getJsonRepresentation(true);

        return new JsonResponse($response);
    }
}
