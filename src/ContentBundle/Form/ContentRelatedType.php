<?php

namespace ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentRelatedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('url', UrlType::class)
            ->add('eng', CheckboxType::class, [
                'required' => false
            ])
            ->add('nsfw', CheckboxType::class, [
                'required' => false
            ])
            ->add('content', EntityType::class, [
                'class' => 'ContentBundle\Entity\Content'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ContentBundle\Entity\ContentRelated'
        ));
    }
}
