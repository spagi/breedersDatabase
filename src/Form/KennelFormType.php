<?php

namespace App\Form;

use App\Entity\Breed;
use App\Entity\Kennel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KennelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'kennel.name',
                'attr' => ['class' => 'form-input'],
            ])
            ->add('breeds', EntityType::class, [
                'class' => Breed::class,
                'choice_label' => 'nameCz',
                'multiple' => true,
                'expanded' => false,
                'label' => 'kennel.breeds',
                'attr' => ['class' => 'form-multiselect'],
                'required' => false,
            ])
            ->add('purposes', ChoiceType::class, [
                'choices' => array_flip(Kennel::PURPOSES),
                'multiple' => true,
                'expanded' => true,
                'label' => 'kennel.purposes',
                'required' => false,
            ])
            ->add('descriptionCz', TextareaType::class, [
                'label' => 'kennel.description_cz',
                'required' => false,
                'attr' => ['class' => 'form-textarea', 'rows' => 5],
            ])
            ->add('descriptionEn', TextareaType::class, [
                'label' => 'kennel.description_en',
                'required' => false,
                'attr' => ['class' => 'form-textarea', 'rows' => 5],
            ])
            ->add('descriptionSk', TextareaType::class, [
                'label' => 'kennel.description_sk',
                'required' => false,
                'attr' => ['class' => 'form-textarea', 'rows' => 5],
            ])
            ->add('city', TextType::class, [
                'label' => 'kennel.city',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('region', TextType::class, [
                'label' => 'kennel.region',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'kennel.country',
                'choices' => [
                    'Česká republika' => 'CZE',
                    'Slovensko' => 'SVK',
                    'Polsko' => 'POL',
                    'Německo' => 'DEU',
                    'Rakousko' => 'AUT',
                ],
                'required' => false,
                'placeholder' => 'form.select_country',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('phone', TextType::class, [
                'label' => 'kennel.phone',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'kennel.email',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('website', UrlType::class, [
                'label' => 'kennel.website',
                'required' => false,
                'attr' => ['class' => 'form-input'],
                'default_protocol' => 'https',
            ])
            ->add('facebook', TextType::class, [
                'label' => 'kennel.facebook',
                'required' => false,
                'attr' => ['class' => 'form-input', 'placeholder' => 'https://facebook.com/...'],
            ])
            ->add('instagram', TextType::class, [
                'label' => 'kennel.instagram',
                'required' => false,
                'attr' => ['class' => 'form-input', 'placeholder' => 'https://instagram.com/...'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Kennel::class]);
    }
}
