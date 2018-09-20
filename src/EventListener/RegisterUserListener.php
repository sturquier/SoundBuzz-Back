<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\User;

class RegisterUserListener
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     *  When register & change password
     */
    private function encodeUserPassword($args)
    {
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $password = $this->encoder->encodePassword($entity, $entity->getPassword());

        $entity->setPassword($password);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if (!$entity instanceof User) {
            return;
        }

        $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);
        if (isset($changeSet['password'])) {
            $password = $this->encoder->encodePassword($entity, $entity->getPassword());

            $entity->setPassword($password);
        }
    }


}