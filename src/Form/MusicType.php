<?php

namespace App\Form;

use App\Entity\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MusicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('photo')
            ->add('is_explicit')
            ->add('downloadable')
            ->add('created_at', DateType::class, ['widget' => 'single_text'])
            // ->add('transfer_at') onCreate Gedmo
            ->add('duration')
            ->add('is_active')
            // ->add('playlists')
            // ->add('user')
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
