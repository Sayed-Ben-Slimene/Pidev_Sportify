<?php

namespace App\Form;

use App\Entity\UGame;
use App\Entity\Votes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ugame', EntityType::class, [
                'class' => UGame::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('scoreTeam1', TextType::class)
            ->add('scoreTeam2', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Votes::class,
        ]);
    }
}
