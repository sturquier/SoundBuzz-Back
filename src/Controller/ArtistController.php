<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Artist;

class ArtistController extends Controller
{
    /**
     * Fetch all artists
     *
     * @Rest\View(serializerGroups={"all_artists"})
     * @Rest\Get("/artists")
     */
    public function getArtistsAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$artists = $em->getRepository(Artist::class)->findAll();

    	return $artists;
    }

    /**
     * Fetch a single artist
     *
     * @Rest\View(serializerGroups={"one_artist"})
     * @Rest\Get("/artists/{id}")
     */
    public function getArtistAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$artist = $em->getRepository(Artist::class)->find($request->get('id'));

    	if (empty($artist)) {
    		return new JsonResponse(['message' => 'Artist not found'], Response::HTTP_NOT_FOUND);
    	}

    	return $artist;
    }
}