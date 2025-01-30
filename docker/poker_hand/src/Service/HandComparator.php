<?php

namespace App\Service;


class HandComparator
{
    const HAND_RANKS = [
        'high_card' => 1,
        'pair' => 2,
        'two_pair' => 3,
        'three_of_a_kind' => 4,
        'straight' => 5,
        'flush' => 6,
        'full_house' => 7,
        'four_of_a_kind' => 8,
        'straight_flush' => 9,
        'royal_flush' => 10,
    ];
    
    
    private $hand_finder_player_1;
    private $hand_finder_player_2;

    public function __construct(HandFinder $hand_finder_player_1, HandFinder $hand_finder_player_2)
    {
        $this->hand_finder_player_1 = $hand_finder_player_1;
        $this->hand_finder_player_2 = $hand_finder_player_2;
    }

    public function did_player_1_win(): int
    {
        $hand_type_1 = $this->hand_finder_player_1->get_type_hand();
        $hand_type_2 = $this->hand_finder_player_2->get_type_hand();

        $rank_player_1 = self::HAND_RANKS[$hand_type_1];
        $rank_player_2 = self::HAND_RANKS[$hand_type_2];

        if($rank_player_1 > $rank_player_2)
        {
            return 1; // Player 1 won
        }
        elseif($rank_player_1 < $rank_player_2)
        {
            return 2; // Player 1 lost
        }
        elseif($rank_player_1 === $rank_player_2)
        {
            // need to check tied poker rules
            return $this->tie_breaker();
        }

        throw new \Exception('Hand comparator cannot verify match result');
    }

    private function tie_breaker(): int
    {
        $type = $this->hand_finder_player_1->get_type_hand();

        $rank_indices_hand_1 = $this->hand_finder_player_1->get_rank_indices();
        $rank_indices_hand_2 = $this->hand_finder_player_2->get_rank_indices();
        
        $type_compare_highest = ['high_card', 'flush', 'straight', 'straight_flush'];

        $type_compare_highest_with_format = ['pair', 'two_pair', 'three_of_a_kind', 'four_of_a_kind', 'full_house'];

        if (in_array($type, $type_compare_highest))
        {
            return $this->compare_highest($rank_indices_hand_1, $rank_indices_hand_2);
        }
        elseif(in_array($type, $type_compare_highest_with_format))
        {
            return $this->compare_highest($this->format_before_compare($rank_indices_hand_1), 
                                            $this->format_before_compare($rank_indices_hand_2));
        }
        elseif($type === 'royal_flush')
        {
            // all Royal Flushes are identical : Tie
            return 3;
        }
        else
        {
            throw new \Exception('Hand comparator Tie breaker type not found');
        }
    }

    private function compare_highest(array $rank_indices_1, $rank_indices_2)
    {
        // same cards
        if($rank_indices_1 === $rank_indices_2)
        {
            return 3;
        }

        // array sorted asc
        // get highest card
        $max_1 = end($rank_indices_1);
        $max_2 = end($rank_indices_2);

        while($max_1 === $max_2){
            // remove highest card
            array_pop($rank_indices_1);
            array_pop($rank_indices_2);

            $max_1 = end($rank_indices_1);
            $max_2 = end($rank_indices_2);
        }

        // if 1 better, player 1 win
        if($max_1 > $max_2)
        {
            return 1;
        }
        // player 2 better, player 1 lost
        elseif($max_1 < $max_2)
        {
            return 2;
        }
        // catching error 
        else
        {
            throw new \Exception('Hand comparator compare high error');
        }
    }

    private function format_before_compare(array $rank_indices)
    {
        // count occurrence
        $counts = array_count_values($rank_indices);

        // sort by occurence (asc) and unique
        uksort($counts, function ($a, $b) use ($counts) {
            return $counts[$a] <=> $counts[$b]; // Sort by frequency
        });

        return array_keys($counts);
    }
}