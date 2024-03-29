<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//        $builder
//            ->add('username')
//            ->add('email')
//            ->add('password'
//                , RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'Wpisane hasła muszą być takie same!',
//                'options' => ['attr' => ['class' => 'password-field']],
//                'required' => true,
//                'first_options'  => ['label' => 'Password'],
//                'second_options' => ['label' => 'Repeat Password'],
//            ]
//            );
//    }
/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email');
    }


}
