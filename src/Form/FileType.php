<?php

namespace App\Form;

use App\Entity\File;
use App\Entity\Folder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as TypeFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as FileConstaint;

class FileType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom : <span class="text-danger">*</span>',
                'label_html' => true
            ])
            ->add('brochure', TypeFileType::class, [
                'label' => 'Fichier(s) : <span class="text-danger">*</span>',
                'label_html' => true,
                'mapped' => false,
                'constraints' => [
                    new FileConstaint([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid type document',
                    ])
                ],
            ])
            ->add('subFolder', EntityType::class, [
                'class' => Folder::class,
                'placeholder' => 'Aucun dossier',
                'required' => false,
                'label' => 'Dans le dossier :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => File::class,
        ]);
    }
}
