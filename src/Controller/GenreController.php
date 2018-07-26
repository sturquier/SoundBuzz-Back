<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Genre;

class GenreController extends Controller
{
    /**
     * Fetch all genres
     *
     * @Rest\View(serializerGroups={"genre"})
     * @Rest\Get("/genres")
     */
    public function getGenresAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$genres = $em->getRepository(Genre::class)->findAll();

    	return $genres;
    }

    /**
     * Fetch a single genre
     *
     * @Rest\View(serializerGroups={"genre", "music", "artist"})
     * @Rest\Get("/genres/{slug}")
     */
    public function getGenreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $genre = $em->getRepository(Genre::class)->findOneBy([
            'slug' => $request->get('slug')
        ]);

        if (empty($genre)) {
            return new JsonResponse(['message' => 'Genre not found'], Response::HTTP_NOT_FOUND);
        }

        return $genre;
    }
}