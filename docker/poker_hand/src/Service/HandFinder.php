<?php

namespace App\Service;


class HandFinder
{
    // rank order
    const RANK_ORDER = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

    private $hand;
    
    private $ranks;
    private $rank_indices;
    private $type_hand;

    public function __construct(array $hand)
    {
        $this->hand = $hand;
        $this->ranks = $this->extract_rank();
        $this->rank_indices = $this->extract_rank_indices();
        $this->type_hand = $this->get_type_of_hand();
    }

    // logic function
    public function get_type_of_hand()
    {
        if($this->is_royal_flush()) return 'royal_flush';

        if($this->is_same_color())
        {
            if($this->is_straight())
            {
                return 'straight_flush';
            }
            else
            {
                return 'flush';
            }
        }
        else
        {
            if($this->is_straight())
            {
                return 'straight';
            }
            else
            {
                // occurrences of each rank
                $rank_counts = array_count_values($this->ranks);

                // Counts the occurrences of each distinct value in an array
                $counts = array_count_values($rank_counts);

                if(isset($counts[4]))
                {
                    return 'four_of_a_kind';
                }
                elseif(isset($counts[3]) && isset($counts[2]))
                {
                    return 'full_house';
                }
                elseif(isset($counts[3]))
                {
                    return 'three_of_a_kind';
                } 
                elseif(isset($counts[2]) && $counts[2] == 2)
                {
                    return 'two_pair';
                } 
                elseif(isset($counts[2]))
                {
                    return 'pair';
                } 
                else
                {
                    return 'high_card';
                }
            }
        }

        // if not found
        return 'type_not_found';
    }

    private function is_royal_flush(): bool
    {
        $royal_flush_hands = [
            ['10H', 'JH', 'QH', 'KH', 'AH'], // Hearts
            ['10D', 'JD', 'QD', 'KD', 'AD'], // Diamonds
            ['10S', 'JS', 'QS', 'KS', 'AS'], // Spades
            ['10C', 'JC', 'QC', 'KC', 'AC'], // Clubs
        ];

        foreach($royal_flush_hands as $royal_flush)
        {
            $intersection = array_intersect($this->hand, $royal_flush);
            
            if (count($intersection) === count($royal_flush)) {
                return true;
            }
        }
        return false;
    }

    private function is_same_color(): bool
    {
        // get color of the first card
        $card_color = substr($this->hand[0], -1);

        // check if all other card is same color
        // no sorting by color because it will take too long 
        // for the little effeciency it will apport in certains case
        foreach($this->hand as $card)
        {
            if(substr($card, -1) !== $card_color) return false;
        }

        return true;
    }

    private function is_straight(): bool
    {
        $rank_indices = $this->rank_indices;

        // Check if the ranks are consecutive
        for ($i = 1; $i < count($rank_indices); $i++) {

            if ($rank_indices[$i] !== $rank_indices[$i - 1] + 1) {
                
                // check for low ace
                // check if actual rank is ace (strongest one)
                // if last strongest one is 5
                // and we have the smallest one is 2
                // so we have an card like 2,3,4,5,A -> it's a straight
                if($rank_indices[$i] === array_search('A', self::RANK_ORDER) 
                && $rank_indices[$i - 1] === array_search('5', self::RANK_ORDER)
                && $rank_indices[0] === array_search('2', self::RANK_ORDER))
                {
                    return true;
                }

                return false;
            }
        }

        return true;
    }

    // helper function
    private function extract_rank()
    {
        // Extract the ranks from the hand, ignoring suits
        $ranks = array_map(function ($card) {
            return substr($card, 0, -1);
        }, $this->hand);

        return $ranks;
    }

    private function extract_rank_indices()
    {
        $rank_order = self::RANK_ORDER;
        // Convert ranks to their corresponding index in the rank order
        $rank_indices = array_map(function ($rank) use ($rank_order) {
            return array_search($rank, $rank_order);
        }, $this->ranks);

        // Sort the indices
        sort($rank_indices);

        return $rank_indices;
    }

    // getter function
    public function get_type_hand()
    {
        return $this->type_hand;
    }

    public function get_hand()
    {
        return $this->hand;
    }

    public function get_ranks()
    {
        return $this->ranks;
    }

    public function get_rank_indices()
    {
        return $this->rank_indices;
    }
}