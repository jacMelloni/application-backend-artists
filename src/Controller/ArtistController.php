<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    private $repository;

    public function __construct(ArtistRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/artists", name="list_artists", methods={"GET"})
     *
     */
    public function getArtistList()
    {
        $artists = $this->repository->findAll();

        $artistArray = [];

        foreach ($artists as $artist) {
            $artistArray[] = $artist->getJsonRepresentation();
        }

        return new JsonResponse($artistArray);
    }

    /**
     * @Route("/artists/{token}", name="get_artist", methods={"GET"})
     *
     */
    public function getArtist($token)
    {
        $artist = $this->repository->findOneBy([
            'token' => $token
        ]);

        $response = $artist? $artist->getJsonRepresentation() : null;

        return new JsonResponse($response);
    }
}
