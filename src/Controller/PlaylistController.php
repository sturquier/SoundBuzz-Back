<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\User;
use App\Entity\Playlist;
use App\Entity\Music;
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

		$currentUser = $em->getRepository(User::class)->find($request->request->get('user'));

		if (empty($currentUser)) {
            return new JsonResponse(['message', 'User not found. Please login to create a playlist'], Response::HTTP_NOT_FOUND);
        }

		$playlist = new Playlist();
		$playlist->setUser($currentUser);

		$form = $this->createForm(PlaylistType::class, $playlist);
		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em->persist($playlist);
			$em->flush();

			return $playlist;
		}

		return $form;
	}

	/**
	 *	Add a single music to a single playlist
	 *
	 *	@Rest\View(serializerGroups={"add_music_to_playlist"})
	 *	@Rest\Post("/playlists/{id}/add-music")
	 */
	public function postMusicToPlaylistAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$playlist = $em->getRepository(Playlist::class)->find($request->get('id'));

		if (empty($playlist)) {
			return new JsonResponse(['message' => 'Playlist not found'], Response::HTTP_NOT_FOUND);
		}

		$music = $em->getRepository(Music::class)->find($request->request->get('music_id'));

		if (empty($music)) {
			return new JsonResponse(['message' => 'Music not found'], Response::HTTP_NOT_FOUND);
		}

		$playlist->addMusic($music);
		
		$em->persist($playlist);
		$em->flush();

		return $playlist;	
	}
}