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
}