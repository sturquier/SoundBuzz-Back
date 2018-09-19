<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Service\GenrePhotoUploader;
use App\Entity\Genre;
use App\Form\GenreType;

class GenreController extends Controller
{
    /**
     * Fetch all genres
     *
     * @Rest\View(serializerGroups={"all_genres"})
     * @Rest\Get("/genres")
     */
    public function getGenresAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$genres = $em->getRepository(Genre::class)->findAll();

    	return $genres;
    }

    /**
     * Fetch a single genre
     *
     * @Rest\View(serializerGroups={"all_genres", "all_musics_of_one_genre"})
     * @Rest\Get("/genres/{slug}")
     */
    public function getGenreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $genre = $em->getRepository(Genre::class)->findOneBy([
            'slug' => $request->get('slug')
        ]);

        if (empty($genre)) {
            return new JsonResponse(['message' => 'Genre not found'], Response::HTTP_NOT_FOUND);
        }

        return $genre;
    }

    /**
     *  Admin only ! Delete a single genre
     *  If the genre got musics & these musics are only related to the 
     *  removed genre => attach these musics to 'default' genre
     *
     *  @Rest\View(serializerGroups={"admin_delete_genre"})
     *  @Rest\Delete("/genres/{id}")
     */
    public function deleteGenreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $genre = $em->getRepository(Genre::class)->find($request->get('id'));

        $defaultGenre = $em->getRepository(Genre::class)->findOneBy(['name' => 'Autre']);

        $genreMusics = $genre->getMusics();
        foreach ($genreMusics as $music) {
            if (count($music->getGenres()) === 1) {
                $defaultGenre->addMusic($music);
            }

            $genre->removeMusic($music);
        }

        $em->remove($genre);
        $em->flush();
    }

    /**
     *  Admin only ! Create a single genre
     *
     *  @Rest\View(serializerGroups={"admin_create_genre"})
     *  @Rest\Post("/genres")
     */
    public function createGenreAction(Request $request, GenrePhotoUploader $uploader)
    {
        $em = $this->getDoctrine()->getManager();
        $genre = new Genre();

        $form = $this->createForm(GenreType::class, $genre);
        $form->submit(array_merge(
            $request->request->all(),
            $request->files->all()
        ));

        if ($form->isValid()) {
            
            $photo = $form->get('photo')->getData();
            $photoName = $uploader->upload($photo);

            $genre->setPhoto($photoName);

            $em->persist($genre);
            $em->flush();

            return $genre;
        }

        return $form;
    }
}