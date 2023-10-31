<?php

namespace AppBundle\Form;

use AppBundle\Entity\Notice;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
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

        $forUserDays = 8;
        $forSuperUserDays = 15;
        if($options['user']->hasRole('ROLE_ADMIN')===true OR $options['user']->hasRole('ROLE_SUPER_USER')===true){
            $days = $forSuperUserDays;
            $defaultWeeks = 2;
        } elseif ($options['user']->hasRole('ROLE_USER')===true) {
            $days = $forUserDays;
            $defaultWeeks = 1;
        }

        $builder
            ->add('title')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Picture (jpg file)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Add picture in .jpg or .jpeg format'
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'placeholder' => 'Choose Notice Category',
                'class' => 'AppBundle:Category',
                'query_builder' => function (CategoryRepository $er){
                return $er->createQueryBuilder('c')->orderBy('c.categoryName', 'ASC');
                },
                'choice_label' => 'category_name',
            ]);
        if($options['user']->hasRole('ROLE_ADMIN')===true) {
            $builder->add('expiration', DateType::class, [
                'data' => new \DateTime("+$defaultWeeks weeks"),
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan("today"),
                    new LessThan("+$days days")
                ]
            ]);
        }
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
