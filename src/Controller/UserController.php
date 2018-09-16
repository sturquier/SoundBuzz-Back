<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\User;
use App\Form\UserType;

class UserController extends Controller
{
    /**
     * Create a single user
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $user;
        }

        return $form;
    }

    /**
     * Get all musics of a single user
     * 
     * @Rest\View(serializerGroups={"user_musics"})
     * @Rest\Get("/users/{id}/musics")
     */
    public function getUserMusicsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $user->getMusics();
    }

    /**
     * Get all playlists of a single user
     *
     * @Rest\View(serializerGroups={"user_playlists"})
     * @Rest\Get("/users/{id}/playlists")
     */
    public function getUserPlaylistsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $user->getPlaylists();
    }

    /**
     *  Edit user profile
     *
     *  @Rest\View(serializerGroups={"user"})
     *  @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User:class)->find($request->request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found']);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $user;
        }

        return $form;
    }
}