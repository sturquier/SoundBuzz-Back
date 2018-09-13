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
     * Impossible to upload a file in this action. Need to do this in 2 part with next action
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"create_one_music"})
     * @Rest\Post("/musics")
     */
    public function postMusicAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $music = new Music();

        $form = $this->createForm(MusicType::class, $music);
        $form->submit($request->request->all());

        if ($form->isValid()) {

            $em->persist($music);
            $em->flush();

            return $music;
        }

        return $form;
    }

    /**
     * Add a single file to an existing music. This action exists bcz it is impossible to create a single music and upload file in the same moment
     *
     * @Rest\View(serializerGroups={"create_one_music"})
     * @Rest\Patch("/musics/{id}/file")
     */
    public function patchFileMusicAction(Request $request, MusicUploader $uploader)
    {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository(Music::class)->find($request->get('id'));

        if (empty($music)) {
            return new JsonResponse(['message' => 'Music not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(MusicType::class, $music);
        $form->submit($request->files->all(), false);

        if ($form->isValid()) {
            $musicFile = $request->request->get('file');
            $fileName = $uploader->upload($musicFile);
            $music->setFile($fileName);

            $em->persist($music);
            $em->flush();

            return $music;
        }

        return $form;
    }
}