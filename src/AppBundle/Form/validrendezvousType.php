<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class validrendezvousType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  ->add('dateheure')
            ->add('prix')
            ->add('promo', ChoiceType::class,array('choices'=>array('No promo'=>'No promo','10%'=>'10%',' 20%'=>' 20%','30%'=>'30%','40%'=>'50%')))
            ->add('etat', ChoiceType::class,array('choices'=>array('At home'=>'At home','Establishment'=>' Establishment')))
            ->add('message')
            ->add('Ajouter', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\validrendezvous'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_validrendezvous';
    }


}
