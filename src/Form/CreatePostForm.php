<?php

namespace App\Form;

use App\DTO\Post\CreatePostRequest;
use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextareaType::class, [
                'attr' => ['placeholder' => 'Let the whole world know your brilliant thoughts 🤡'],
                'label' => false
            ])
            ->add('access', ChoiceType::class, [
                'choices' => [
                    'Public' => 'Public',
                    'Followers' => 'Followers'
                ],
                'label' => "Select audience:"
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreatePostRequest::class,
        ]);
    }
}
