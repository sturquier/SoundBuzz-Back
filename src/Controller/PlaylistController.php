<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Playlist;
use App\Form\PlaylistType;

class PlaylistController extends Controller
{

	/**
	 *	Create a single playlist
	 *
	 *	@Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"create_one_playlist"})
	 *	@Rest\Post("/playlists")
	 */
	public function postPlaylistAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$playlist = new Playlist();

		$form = $this->createForm(PlaylistType::class, $playlist);
		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em->persist($playlist);
			$em->flush();

			return $playlist;
		}

		return $form;
	}
}