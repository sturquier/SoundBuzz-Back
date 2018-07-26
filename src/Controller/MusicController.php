<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Music;

class MusicController extends Controller
{

    /**
     * Fetch a single music
     *
     * @Rest\View(serializerGroups={"one_music"})
     * @Rest\Get("/musics/{id}")
     */
    public function getMusicAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$music = $em->getRepository(Music::class)->find($request->get('id'));

    	if (empty($music)) {
    		return new JsonResponse(['message' => 'Music not found'], Response::HTTP_NOT_FOUND);
    	}

    	return $music;
    }
}