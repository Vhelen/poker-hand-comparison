<?php

namespace App\Controller;

use App\Service\CardChecker;
use App\Service\HandComparator;
use App\Service\HandFinder;

use App\Form\PokerHandType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class PokerHandController extends AbstractController
{
    #[Route('/', name: 'poker_hand', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(PokerHandType::class);

        $form->handleRequest($request);

        $res = null;

        if ($form->isSubmitted())
        {
            if($form->isValid()) {
                $data = $form->getData();

                $hand_player_1 = $data['hand_player_1'];
                $hand_player_2 = $data['hand_player_2'];
                
                try
                {
                    // CardChecker 
                    // check for good format
                    // transform string hand to array 
                    // check for duplicate card
                    $card_checker = new CardChecker($hand_player_1, $hand_player_2);

                    // HandFinder
                    // Find type of poker hand
                    $hand_finder_player_1 = new HandFinder($card_checker->get_hand_player_1());
                    $hand_finder_player_2 = new HandFinder($card_checker->get_hand_player_2());

                    // HandComparator
                    // check if a poker hand type is stronger
                    // yes -> player one or player 2 has won
                    // no -> same poker hand type, check for tie rule
                    $hand_comparator = new HandComparator($hand_finder_player_1, $hand_finder_player_2);

                    $res = $hand_comparator->did_player_1_win();

                    return new JsonResponse(['success' => true, 'result' => $res]);
                }
                catch(\Exception $e)
                {
                    return new JsonResponse(['success' => false, 'errors' => [$e->getMessage()]]);
                }
                
            }
            else
            {
                return new JsonResponse(['success' => false, 'errors' => $this->get_form_errors($form)]);
            }
        }

        return $this->render('poker_hand/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function get_form_errors($form): array
    {
        $errors = [];

        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        
        return $errors;
    }
}
