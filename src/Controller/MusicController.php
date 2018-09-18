<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Service\MusicUploader;
use App\Entity\Music;
use App\Form\MusicType;

class MusicController extends Controller
{

    /**
     * Fetch all musics (admin only !)
     *
     * @Rest\View(serializerGroups={"admin_all_musics"})
     * @Rest\Get("/musics")
     */
    public function getMusicsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $musics = $em->getRepository(Music::class)->findAllByAscTitle();

        return $musics;
    }

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

    /**
     * Create a single music
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"create_one_music"})
     * @Rest\Post("/musics")
     */
    public function postMusicAction(Request $request, MusicUploader $uploader)
    {
        $em = $this->getDoctrine()->getManager();
        $music = new Music();

        $form = $this->createForm(MusicType::class, $music);
        $form->submit($request->request->all());
        if ($form->isValid()) {

            return $form;
            $musicFile = file_get_contents($request->request->get('file'));
            $fileName = $uploader->upload($musicFile);

            return $fileName;

            $music->setFile($fileName);

            $em->persist($music);
            $em->flush();

            return $music;
        }

        return $form;
    }

    /**
     * Increment downloads number of a single music
     * 
     * @Rest\View(serializerGroups={"download_music"})
     * @Rest\Patch("/musics/{id}/download")
     */
    public function downloadMusicAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository(Music::class)->find($request->get('id'));
    
        if (empty($music)) {
            return new JsonResponse(['message' => 'Music not found'], Response::HTTP_NOT_FOUND);
        }

        $music->incrementDownloads();

        $em->persist($music);
        $em->flush();
    }
}