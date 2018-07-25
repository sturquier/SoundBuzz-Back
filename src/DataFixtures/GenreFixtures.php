<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Genre;

class GenreFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{

		$genre1 = new Genre();
		$genre1->setName('Musique classique');
		$manager->persist($genre1);

		$genre2 = new Genre();
		$genre2->setName('Reggae');
		$manager->persist($genre2);

		$genre3 = new Genre();
		$genre3->setName('Hip Hop');
		$manager->persist($genre3);

		$genre4 = new Genre();
		$genre4->setName('Metal');
		$manager->persist($genre4);

		$genre5 = new Genre();
		$genre5->setName('Electronique');
		$manager->persist($genre5);

		$manager->flush();
	}
}