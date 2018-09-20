<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Artist;
use Faker;
use RauweBieten\PhpFakerMusic;
use App\DataFixtures\GenreFixtures;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
	// public function load(ObjectManager $manager)
	// {
	// 	$faker = Faker\Factory::create();
	// 	$faker->addProvider(new PhpFakerMusic\Classical($faker));
	// 	$faker->addProvider(new PhpFakerMusic\Reggae($faker));
	// 	$faker->addProvider(new PhpFakerMusic\HipHop($faker));
	// 	$faker->addProvider(new PhpFakerMusic\Metal($faker));
	// 	$faker->addProvider(new PhpFakerMusic\Dance($faker));
		
	// 	for ($i = 0; $i < 100; $i++) {
	// 		$artist = new Artist();

	// 		$random_type = mt_rand(1, 3);
	// 		$artist->setType($random_type);

	// 		// 5 genres in GenreFixtures
	// 		$random_genre = mt_rand(1, 5);
	// 		switch ($random_genre) {
	// 			case 1:
	// 				$artist->setName($faker->musicClassicalArtist());
	// 				break;
	// 			case 2:
	// 				$artist->setName($faker->musicReggaeArtist());
	// 				break;
	// 			case 3:
	// 				$artist->setName($faker->musicHipHopArtist());
	// 				break;
	// 			case 4:
	// 				$artist->setName($faker->musicMetalArtist());
	// 				break;
	// 			case 5:
	// 				$artist->setName($faker->musicDanceArtist());
	// 				break;
	// 		}

	// 		$this->addReference('artist_'.$i, $artist);

	// 		$manager->persist($artist);

	// 		$manager->flush();
	// 	}
	// }

	// public function getDependencies()
	// {
	// 	return [
	// 		GenreFixtures::class,
	// 	];
	// }
}