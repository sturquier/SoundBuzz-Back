<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{

		$user1 = new User();
		$user1->setFirstname('Simon');
		$user1->setLastName('Turquier');
		$user1->setUsername('sturquier');
		$user1->setPassword('sturquier');
		$user1->setEmail('turquiersimon@hotmail.fr');
		$user1->setRole('ROLE_ADMIN');
		$user1->setIsArtist(false);
		$user1->setBirthday(new \DateTime('1996-02-23'));
		$manager->persist($user1);

		$manager->flush();
	}
}