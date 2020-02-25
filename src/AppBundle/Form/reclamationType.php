<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class reclamationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('sujet', ChoiceType::class,array('choices'=>array('Other'=>'Other',' About our  application'=>' About our  application','About our events'=>'About our events','About our news'=>'About our news','About our repairers'=>'About our repairers','About our sales'=>'About our sales','About our rentals'=>'About our rentals','About our shopping'=>'About our shopping')))
            ->add('message')

            ->add('Add', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\reclamation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reclamation';
    }


}
