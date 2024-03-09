<?php


namespace App\Form;

use App\Entity\Nicotine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NicotineChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nicotine', EntityType::class, [
                'class' => Nicotine::class,
                'choice_label' => 'proportioning',
                'label' => 'nicotine',
              'multiple' => true,
              'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nicotine::class,
        ]);
    }
}
