<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\User;
use App\Entity\Music;
use App\Entity\Like;
use App\Form\UserType;
use App\Form\LikeType;

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
        $user = $em->getRepository(User::class)->find($request->get('id'));

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

    /**
     *  Admin only !
     *  Fetch all users
     *
     * @Rest\View(serializerGroups={"admin_all_users"})
     * @Rest\Get("/users")
     *
     */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAllByAscUsername();

        return $users;
    }

    /**
     *  Admin only !
     *  Delete a single user.
     *  An admin cannot delete another admin => avoid god admin
     *
     *  @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"admin_delete_user"})
     *  @Rest\Delete("/users/{id}")
     */
    public function deleteUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if ($user->getRole() === 'ROLE_ADMIN') {
            return new JsonResponse(['message' => 'You cannot delete an administrator ;)'], Response::HTTP_FORBIDDEN);
        }

        foreach ($user->getLikes() as $like) {
            $user->removeLike($like);
        }

        foreach ($user->getPlaylists() as $playlist) {
            $user->removePlaylist($playlist);
        }

        foreach ($user->getComments() as $comment) {
            $user->removeComment($comment);
        }

        foreach ($user->getListens() as $listen) {
            $user->removeListen($listen);
        }

        foreach ($user->getMusics() as $music) {
            $user->removeMusic($music);
        }

        $em->remove($user);
        $em->flush();
    }

    /**
     *  Like / Remove like of a single music
     *
     *  @Rest\View(serializerGroups={"user_likes"})
     *  @Rest\Post("/users/{id}/like")
     */
    public function likeMusicAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $isAlreadyLiked = false;

        $currentUser = $em->getRepository(User::class)->find($request->get('id'));
        if (empty($currentUser)) {
            return new JsonResponse(['message' => 'You need to log in to like a music'], Response::HTTP_FORBIDDEN);
        }

        $music = $em->getRepository(Music::class)->find($request->request->get('music'));
        if (empty($music)) {
            return new JsonResponse(['message' => 'Music not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if already liked
        foreach ($currentUser->getLikes() as $like) {
            if ($like->getMusic()->getId() === $request->request->get('music')) {
                $isAlreadyLiked = true;
            }
        }

        if ($isAlreadyLiked === true) {
            $likeToRemove = $em->getRepository(Like::class)->findOneBy([
                'user'  => $currentUser,
                'music' => $music
            ]);
            $em->remove($likeToRemove);
        } else {
            $like = new Like();
            $form = $this->createForm(LikeType::class, $like);
            $form->submit($request->request->all());

            if ($form->isValid()) {
                $like->setUser($currentUser);
                $like->setMusic($music);

                $em->persist($like);
            }
        }

        $em->flush();
    }
}