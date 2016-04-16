<?php

namespace ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('eng', TextType::class, [
                'required' => true
            ])
            ->add('nsfw', TextType::class, [
                'required' => true
            ])
            ->add('channels', EntityType::class, [
                'class' => 'ChannelBundle\Entity\Channel',
                'required' => true,
                'empty_data'  => false,
                'multiple' => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ContentBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'content';
    }
}
