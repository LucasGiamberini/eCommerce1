<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AddAdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',  TextType::class,['label' => 'Prenom', 'label_attr' => ['class' => 'label'],])
            ->add('name',  TextType::class,['label' => 'Nom' , 'label_attr' => ['class' => 'label'],])
            ->add('adress', TextType::class,['label' => 'Adresse', 'label_attr' => ['class' => 'label'],])
            ->add('postalCode', IntegerType::class , ['label' => 'Code Postal', 'label_attr' => ['class' => 'label'],])
            ->add('City', TextType::class,['label' => 'Ville', 'label_attr' => ['class' => 'label'],])
     //       ->add('total')
    //        ->add('account', EntityType::class, [
    //            'class' => Account::class,
//'choice_label' => 'id',
  //          ])
            ->add('Valider', SubmitType::Class, ['attr' => ['class' => 'confirm']]  )   
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
