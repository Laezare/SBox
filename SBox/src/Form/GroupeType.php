<?php

namespace App\Form;

use App\Entity\Groups;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('photo')
            //->add('date')
            -> add('users', EntityType::class, array(
                'class' => User::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'username',
            ));
            //->add('user_admin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groups::class,
        ]);
    }
}
