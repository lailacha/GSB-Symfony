<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Fichefrais;
use App\Entity\FicheSearch;
use App\Entity\Visiteur;
use App\Repository\FicheFraisRepository;
use App\Repository\FraisForfaitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichefraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idvisiteur', EntityType::class, ['class' => Visiteur::class,
                'placeholder' => 'Choisir un visiteur',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('mois', EntityType::class, ['class' => Fichefrais::class,
                'placeholder' => 'Tous les mois',
                'required' => false,
                'query_builder' => function (FicheFraisRepository $er){
                    return $er->createQueryBuilder('f')
                        ->groupBy('f.mois');
                },
                'choice_label' => 'mois',
                'attr' => ['class' => 'form-control']
            ])
            ->add('idetat', EntityType::class, ['label' => 'Etat', 'class' => Etat::class,
                'placeholder' => 'Tous les etats',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])


            ->add('save', SubmitType::class, ['label' => 'Envoyer',
                'attr' => ['class' => 'm-4 btn btn-primary']])


        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => Fichefrais::class,
            'method'          => 'get',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}