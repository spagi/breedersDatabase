<?php

namespace App\Form;

use App\Entity\Breed;
use App\Entity\Dog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DogFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'dog.name', 'attr' => ['class' => 'form-input']])
            ->add('gender', ChoiceType::class, [
                'label' => 'dog.gender',
                'choices' => array_flip(Dog::GENDERS),
                'attr' => ['class' => 'form-select'],
            ])
            ->add('breed', EntityType::class, [
                'class' => Breed::class,
                'choice_label' => 'nameCz',
                'label' => 'dog.breed',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('bornAt', DateType::class, [
                'label' => 'dog.born_at',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('titles', TextType::class, ['label' => 'dog.titles', 'required' => false, 'attr' => ['class' => 'form-input']])
            ->add('registrationNumber', TextType::class, ['label' => 'dog.registration_number', 'required' => false, 'attr' => ['class' => 'form-input']])
            ->add('chipNumber', TextType::class, ['label' => 'dog.chip_number', 'required' => false, 'attr' => ['class' => 'form-input']])
            ->add('isStud', CheckboxType::class, ['label' => 'dog.is_stud', 'required' => false])
            ->add('description', TextareaType::class, ['label' => 'dog.description', 'required' => false, 'attr' => ['class' => 'form-textarea', 'rows' => 4]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Dog::class]);
    }
}
