<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Music;
use Faker;
use RauweBieten\PhpFakerMusic;
use App\DataFixtures\ArtistFixtures;

class MusicFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$faker = Faker\Factory::create();
		$faker->addProvider(new PhpFakerMusic\Classical($faker));
		$faker->addProvider(new PhpFakerMusic\Reggae($faker));
		$faker->addProvider(new PhpFakerMusic\HipHop($faker));
		$faker->addProvider(new PhpFakerMusic\Metal($faker));
		$faker->addProvider(new PhpFakerMusic\Dance($faker));
		
		for ($i = 0; $i < 100; $i++) {
			$music = new Music();

			$music->setDescription($faker->text($maxNbChars = 50));
			$music->setFile('Music file');
			$music->setIsExplicit(false);
			$music->setDownloadable(true);
			$music->setCreatedAt($faker->date($format = 'Y-m-d', $max = 'now'));
			$music->setDuration($faker->numberBetween($min = 120, $max = 600));
			$music->setIsActive(true);
			$music->setDownloads(0);
			$music->addArtist($this->getReference('artist_'.$i));

			// 5 genres in GenreFixtures
			$random_genre = mt_rand(1, 5);
			switch ($random_genre) {
				case 1:
					$music->setTitle($faker->musicClassicalAlbum());
					$music->addGenre($this->getReference('genre_1'));
					break;
				case 2:
					$music->setTitle($faker->musicReggaeAlbum());
					$music->addGenre($this->getReference('genre_2'));
					break;
				case 3:
					$music->setTitle($faker->musicHipHopAlbum());
					$music->addGenre($this->getReference('genre_3'));
					break;
				case 4:
					$music->setTitle($faker->musicMetalAlbum());
					$music->addGenre($this->getReference('genre_4'));
					break;
				case 5:
					$music->setTitle($faker->musicDanceAlbum());
					$music->addGenre($this->getReference('genre_5'));
					break;
			}

			$manager->persist($music);

			$manager->flush();
		}
	}

	public function getDependencies()
	{
		return [
			ArtistFixtures::class,
		];
	}
}