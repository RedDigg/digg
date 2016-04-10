<?php

namespace ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('content', IntegerType::class)
            ->add('title', TextType::class)
            ->add('url', UrlType::class)
            ->add('eng', CheckboxType::class, [
                'required' => false
            ])
            ->add('nsfw', CheckboxType::class, [
                'required' => false
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

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'content_related';
    }
}
