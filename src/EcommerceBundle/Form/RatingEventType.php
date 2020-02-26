<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 25/02/2018
 * Time: 21:49
 */

namespace EcommerceBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('rating',RatingType::class,['label'=> 'Rating'])

            ->add('Ajouter',SubmitType::class,
                array('label'=>'Evaluer','attr' =>array('class'=>'button button-border button-border-thin button-amber')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcommerceBundle\Entity\Velo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecommercebundle_ecommerce';
    }





}