<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Genre;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;

class GenreFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager)
	{

		$genre1 = new Genre();
		$genre1->setName('Musique classique');
		$genre1->setPhoto('genre-classique.jpg');
		$this->addReference('genre_1', $genre1);
		$manager->persist($genre1);

		$genre2 = new Genre();
		$genre2->setName('Reggae');
		$genre2->setPhoto('genre-reggae.jpg');
		$this->addReference('genre_2', $genre2);
		$manager->persist($genre2);

		$genre3 = new Genre();
		$genre3->setName('Hip Hop');
		$genre3->setPhoto('genre-hiphop.jpg');
		$this->addReference('genre_3', $genre3);
		$manager->persist($genre3);

		$genre4 = new Genre();
		$genre4->setName('Metal');
		$genre4->setPhoto('genre-metal.jpg');
		$this->addReference('genre_4', $genre4);
		$manager->persist($genre4);

		$genre5 = new Genre();
		$genre5->setName('Electronique');
		$genre5->setPhoto('genre-electronic.jpg');
		$this->addReference('genre_5', $genre5);
		$manager->persist($genre5);

		$defaultGenre = new Genre();
		$defaultGenre->setName('Autre');
		$defaultGenre->setPhoto('genre-autres.jpg');
		$manager->persist($defaultGenre);

		$manager->flush();
	}

	public function getDependencies()
	{
		return [
			UserFixtures::class,
		];
	}
}