<?php

namespace App\Form;

use App\Entity\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('file')
            // ->add('photo')
            ->add('is_explicit')
            ->add('downloadable')
            ->add('created_at')
            // ->add('transfer_at') onCreate Gedmo
            ->add('is_active')
            // ->add('playlists')
            ->add('user')
            // ->add('genres')
            // ->add('artists')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => Music::class,
            'csrf_protection'   => false,
        ]);
    }
}
