<?php

namespace AppBundle\Form;

use AppBundle\Entity\Notice;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\File;

class NoticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dump($options);
//        die;

        $forUserDays = 8;
        $forSuperUserDays = 15;
        if($options['user']->hasRole('ROLE_ADMIN') OR $options['user']->hasRole('ROLE_SUPER_USER')){
            $days = $forSuperUserDays;
            $defaultWeeks = 2;
        } elseif ($options['user']->hasRole('ROLE_USER')) {
            $days = $forUserDays;
            $defaultWeeks = 1;
        }
//        dump($options, $days, $options['user']->hasRole('ROLE_SUPER_USER'));
//        die;

        $builder
            ->add('title')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Zdjęcie (jpg file)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Dodaj zdjęcie w formacie .jpg lub .jpeg'
                    ])
                ]

            ])
            ->add('expiration', DateType::class, [
                'data' => new \DateTime("+$defaultWeeks weeks"),
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan("today"),
                    new LessThan("+$days days")
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
        $resolver->setRequired('user');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_notice';
    }


}
