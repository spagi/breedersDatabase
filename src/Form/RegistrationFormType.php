<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'form.first_name',
                'attr' => ['class' => 'form-input', 'placeholder' => 'form.first_name'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'form.last_name',
                'attr' => ['class' => 'form-input', 'placeholder' => 'form.last_name'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'attr' => ['class' => 'form-input', 'placeholder' => 'form.email'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'form.password',
                'attr' => ['class' => 'form-input', 'autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'validation.password_required']),
                    new Length(['min' => 6, 'minMessage' => 'validation.password_min', 'max' => 4096]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'form.agree_terms',
                'constraints' => [new IsTrue(['message' => 'validation.agree_terms'])],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
