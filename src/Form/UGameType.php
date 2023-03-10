<?php

namespace App\Form;

use App\Entity\UGame;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('dateTime', DateTimeType::class,  [
                'label' => 'Pick a date and time:',
                'widget' => 'single_text',
                'input'  => 'datetime',
                'attr' => ['class' => 'datetimepicker'],
            ])
            ->add('team1', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ])

            ->add('team2', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UGame::class,
        ]);
    }
}
