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
		$manager->persist($user1);

		$user2 = new User();
		$user2->setFirstname('Sarah');
		$user2->setLastName('Abderemane');
		$user2->setUsername('starofthemoon');
		$user2->setPassword('starofthemoon');
		$user2->setEmail('sarahabderemane@gmail.com');
		$user2->setRole('ROLE_ADMIN');
		$manager->persist($user2);

		$user3 = new User();
		$user3->setFirstname('Pathe');
		$user3->setLastName('Barry');
		$user3->setUsername('kyller92');
		$user3->setPassword('kyller92');
		$user3->setEmail('pathe.barry92@gmail.com');
		$user3->setRole('ROLE_ADMIN');
		$manager->persist($user3);

		$user4 = new User();
		$user4->setFirstname('Sophie');
		$user4->setLastName('Lee');
		$user4->setUsername('stitchmoonz');
		$user4->setPassword('stitchmoonz');
		$user4->setEmail('lee.sophie0@gmail.com');
		$user4->setRole('ROLE_ADMIN');
		$manager->persist($user4);

		$manager->flush();
	}
}