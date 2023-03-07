<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label'=>'image(jpg or png)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_uri'=>false,
                'imagine_pattern' => 'squared_output_small'
                
                
        ])
            ->add('adress', ChoiceType::class, ['choices'=>[
                'Antananarivo'=>'Antananarivo',
                'Mahajanga'=>'Mahajanga',
                'Tamatave'=>'toamasina',
                'Fianarantsoa'=>'Fianarantsoa',
                'Diego'=>'Diego',
                'Tolara'=>'Tulear',
                'Antsirabe'=>'Antsirabe',

            ]])
         
        
            ->add('profession', ChoiceType::class, ['choices'=>[
                'Agroalimentaire'=>'culitivateur, eleveur',
                'vendeur'=>'vente d\'articles',
                'enseignants'=>'ecole, universite',
                'sante'=>'medecin,infirmiere, chirurgien ...',
                'BTP'=>'batiment',
                'etudiant'=>'premiere cycle, troisieme cycle',
                'entrepreneur'=>'PDG, proprietaire',
                'chauffeur'=>'taxi, camion, bus ...',
                'ressource humaine'=>'chasseur de tete',
                'autres'=>'autre'
            ]])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phoneNumber', IntegerType::class)
            ->add('email', EmailType::class, ['label'=>'e-mail'])
            ->add('birthAt', BirthdayType::class)
            ->add('username', TextType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
