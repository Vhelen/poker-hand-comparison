<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PokerHandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hand_player_1', TextType::class, [
                'label' => 'Poker Hand 1',
                'attr' => ['placeholder' => 'e.g., 2C 7D 5H AS 4C', 'class' => 'poker-input'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Player 1 hand is required.']),
                    new Assert\Length([
                        'min' => 14,
                        'max' => 14,
                        'exactMessage' => 'Poker hand must be exactly {{ limit }} characters long.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^([2-9TJQKA][SHDC]\s){4}([2-9TJQKA][SHDC])$/',
                        'message' => 'Use the correct card format (e.g., 2C 7D 5H AS 4C).',
                    ]),
                ],
            ])
            ->add('hand_player_2', TextType::class, [
                'label' => 'Poker Hand 2',
                'attr' => ['placeholder' => 'e.g., 3H 6S 9D QH 10C', 'class' => 'poker-input'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Player 2 hand is required.']),
                    new Assert\Length([
                        'min' => 14,
                        'max' => 14,
                        'exactMessage' => 'Poker hand must be exactly {{ limit }} characters long.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^([2-9TJQKA][SHDC]\s){4}([2-9TJQKA][SHDC])$/',
                        'message' => 'Use the correct card format (e.g., 3H 6S 9D QH 10C).',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

