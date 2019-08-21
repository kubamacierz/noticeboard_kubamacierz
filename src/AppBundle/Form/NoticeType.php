<?php

namespace AppBundle\Form;

use AppBundle\Entity\Notice;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NoticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')->add('description')->add('image')->add('expiration')
        ->add('category', EntityType::class, [
            'placeholder' => 'Wybierz kategorię ogłoszenia',
            'class' => 'AppBundle:Category',
            'query_builder' => function(CategoryRepository $er){
                return $er->createQueryBuilder('c')->orderBy('c.category_name', 'ASC');
            },
//            'choice_label' => 'category_name'
        ])
        ;
    }
//$builder->add('users', EntityType::class, [ 'class' => 'AppBundle:User', 'query_builder' => function (EntityRepository $er) { return $er->createQueryBuilder('u') ->orderBy('u.username', 'ASC'); }, 'choice_label' => 'username', ]);

    /**
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
