<?php

namespace App\Form;

use App\Entity\Dog;
use App\Entity\Kennel;
use App\Entity\Litter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LitterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Kennel $kennel */
        $kennel = $options['kennel'];

        $builder
            ->add('litterLetter', TextType::class, [
                'label' => 'litter.letter',
                'required' => false,
                'attr' => ['class' => 'form-input', 'maxlength' => 5],
            ])
            ->add('isPlanned', CheckboxType::class, ['label' => 'litter.is_planned', 'required' => false])
            ->add('bornAt', DateType::class, [
                'label' => 'litter.born_at',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('availableFrom', DateType::class, [
                'label' => 'litter.available_from',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-input'],
            ])
            ->add('mother', EntityType::class, [
                'class' => Dog::class,
                'choices' => $kennel->getDogs()->filter(fn($d) => !$d->isMale()),
                'choice_label' => 'name',
                'label' => 'litter.mother',
                'required' => false,
                'placeholder' => 'form.select_dog',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('father', EntityType::class, [
                'class' => Dog::class,
                'choice_label' => 'name',
                'label' => 'litter.father',
                'required' => false,
                'placeholder' => 'form.select_dog',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('totalPuppies', IntegerType::class, ['label' => 'litter.total_puppies', 'attr' => ['class' => 'form-input', 'min' => 0]])
            ->add('malePuppies', IntegerType::class, ['label' => 'litter.male_puppies', 'attr' => ['class' => 'form-input', 'min' => 0]])
            ->add('femalePuppies', IntegerType::class, ['label' => 'litter.female_puppies', 'attr' => ['class' => 'form-input', 'min' => 0]])
            ->add('hasPuppiesAvailable', CheckboxType::class, ['label' => 'litter.has_puppies_available', 'required' => false])
            ->add('description', TextareaType::class, [
                'label' => 'litter.description',
                'required' => false,
                'attr' => ['class' => 'form-textarea', 'rows' => 4],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Litter::class]);
        $resolver->setRequired('kennel');
    }
}
