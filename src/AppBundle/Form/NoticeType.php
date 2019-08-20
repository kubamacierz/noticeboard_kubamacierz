<?php

namespace AppBundle\Form;

use AppBundle\Entity\Notice;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;

class NoticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')->add('description')->add('image')->add('expiration')
//        ->add('category', EntityType::class, [
//            'placeholder' => 'Wybierz kategorię ogłoszenia',
//            'class' => Category::class,
//            'query_builder' => function(CategoryRepository $repo){
//                return $repo->findAllCategories();
//            },
//            'choice_label' => 'categoty_name'
//        ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Notice'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_notice';
    }


}
