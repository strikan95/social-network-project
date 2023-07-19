<?php

namespace App\Form;

use App\Entity\Profile;
use App\Controller\ProfileController;
use App\Form\DataTransformer\FileToPathTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class SettingsType extends AbstractType
{
    private string $uploadPath;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('briefDescription', TextareaType::class, [
                'attr' => ['placeholder' => 'Introduce your profile'],
                'label' => 'Introduction'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['placeholder' => 'Describe yourself']
            ])
            ->add('backgroundImage', FileType::class,  [
                'label' => 'Background',
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File(['mimeTypes' => ['image/png', 'image/jpeg'], 'mimeTypesMessage' => 'Please upload only image formats'])
                ]
            ])
            ->add('profileImage', FileType::class, [
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File(['mimeTypes' => ['image/png', 'image/jpeg'], 'mimeTypesMessage' => 'Please upload only image formats'])
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
  }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }

}
