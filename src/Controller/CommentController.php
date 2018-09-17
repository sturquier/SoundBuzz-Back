<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Music;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;

class CommentController extends Controller
{

	/**
     * Add a single comment to a single music
     *
     * @Rest\View(serializerGroups={"add_comment"})
     * @Rest\Post("/musics/{id}/add-comment")
     */
    public function postCommentToMusicAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository(Music::class)->find($request->get('id'));

        if (empty($music)) {
            return new JsonResponse(['message' => 'Music not found'], Response::HTTP_NOT_FOUND);
        }

        $currentUser = $em->getRepository(User::class)->find($request->request->get('user'));

        if (empty($currentUser)) {
            return new JsonResponse(['message', 'User not found. Please login to add comment to this music'], Response::HTTP_NOT_FOUND);
        }

        $comment = new Comment();
        $comment->setMusic($music);
        $comment->setUser($currentUser);

        $form = $this->createForm(CommentType::class, $comment);
        $form->submit($request->request->all());

        if ($form->isValid()) {

            $em->persist($comment);
            $em->flush();

            return $comment;
        }

        return $form;
    }
}