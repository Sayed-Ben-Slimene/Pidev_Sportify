<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateTime',DateType::class,  [
                'widget' => 'single_text',
                'input'  => 'datetime'
            ])
            ->add('team1',EntityType::class,[
                'class' => Team::class,
                'choice_label' => 'name',
                'attr' => ['class'=> 'form-control']
            ])
            ->add('scoreTeam1',IntegerType::class)

            ->add('team2',EntityType::class,[
                'class' => Team::class,
                'choice_label' => 'name',
                'attr' => ['class'=> 'form-control']
            ])
            ->add('scoreTeam2',IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
