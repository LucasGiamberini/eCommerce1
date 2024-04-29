<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    
        ->add('email',EmailType::class, [
            'label' => 'Email',
            'required' => true,
            'label_attr' => ['id' => 'labelEmailEditUser'],
            'label_attr' => ['class' => 'label']
        ])
            ->add('plainPassword', RepeatedType::class, [
             
                'type' => PasswordType::class,
                'label_attr' => ['class' => 'label'],
                'required' => false,
                'options' => [
                    'required' => false,
                    'attr' => [
                        'autocomplete' => 'new-password',
                       
                    ],
                ],
                'first_options' => [
                   
                    'constraints' => [
                     
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Votre mot de passe doit contenir aux moin {{ limit }} caracteres',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{12,64}$/',
                            'message' => 'Le mot de passe doit etre composer de au moin une lettre majuscule,une miniscule et un caractère spécial '
                        ])
    
                    ],
                    'label' => 'Nouveau mot de passe',
                    'label_attr' => ['class' => 'label']
                ],
                'second_options' => [
                    'label' => 'Repeter mot de passe',
                    'label_attr' => ['class' => 'label']
                ],
                'invalid_message' => 'Les mots de passe ne corresponde pas.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('Valider', SubmitType::Class,
            [ 'attr' => ['class' => 'blueButton' ] ])   
            ;
           
        ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
