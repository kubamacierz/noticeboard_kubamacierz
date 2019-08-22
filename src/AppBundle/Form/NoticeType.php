<?php

namespace AppBundle\Form;

use AppBundle\Entity\Notice;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class NoticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('image')
            ->add('expiration', DateType::class, [
                'data' => new \DateTime('+2 weeks'),
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan("today")
                ]
            ])
            ->add('category', EntityType::class, [
                'placeholder' => 'Wybierz kategorię ogłoszenia',
                'class' => 'AppBundle:Category',
                'query_builder' => function (CategoryRepository $er){
                return $er->createQueryBuilder('c')->orderBy('c.categoryName', 'ASC');
                },
                'choice_label' => 'category_name',
            ]);
    }

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
