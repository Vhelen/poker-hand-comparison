<?php

namespace App\Tests\Service;

use App\Service\HandComparator;
use App\Service\HandFinder;

use PHPUnit\Framework\TestCase;

class HandComparatorTest extends TestCase
{
    public function test_player_1_win(): void
    {
        $hand_player_1 = ['9H', '10H', 'JH', 'QH', 'KH']; // Straight Flush
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['10H', 'JS', 'QS', 'KC', 'AH']; // Straight high ace
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(1, $hand_comparator->did_player_1_win());
    }

    public function test_player_1_lost(): void
    {
        $hand_player_1 = ['2H', '4D', '7S', '9C', 'KH']; // High Card
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['3H', '3D', '9S', '9C', 'KH']; // Two Pair
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(2, $hand_comparator->did_player_1_win());
    }

    public function test_royal_flush(): void
    {
        $hand_player_1 = ['10H', 'JH', 'QH', 'KH', 'AH']; // Royal Flush
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['10C', 'JC', 'QC', 'KC', 'AC']; // Royal Flush
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(3, $hand_comparator->did_player_1_win());
    }

    public function test_highest_card(): void
    {
        $hand_player_1 = ['2H', '4D', '7S', '9C', 'KH']; // High Card
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['2C', '3C', '7C', '9D', 'KC']; // High Card
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(1, $hand_comparator->did_player_1_win());
    }

    public function test_tie_highest_card(): void
    {
        $hand_player_1 = ['2H', '4D', '7S', '9C', 'KH']; // High Card
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['2C', '4C', '7C', '9D', 'KC']; // High Card
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(3, $hand_comparator->did_player_1_win());
    }

    public function test_pair()
    {
        $hand_player_1 =  ['4H', '4D', '7S', '9C', 'KH']; // pair
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['2C', '4C', '7C', '7D', 'KC']; // pair
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(2, $hand_comparator->did_player_1_win());
    }


    public function test_full_house()
    {
        $hand_player_1 =  ['4H', '4D', '4S', '9C', '9H']; // full house
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['4C', '4D', '4H', '7D', '7C']; // full house
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(1, $hand_comparator->did_player_1_win());
    }


    public function test_four_of_a_kind()
    {
        $hand_player_1 =  ['4H', '4D', '4S', '4C', 'AH']; // Four of a Kind
        shuffle($hand_player_1); // shuffle hand

        $hand_player_2 = ['4C', '4D', '4H', '4S', '2C']; // Four of a Kind
        shuffle($hand_player_2); // shuffle hand

        $hand_finder_1 = new HandFinder($hand_player_1);
        $hand_finder_2 = new HandFinder($hand_player_2);

        $hand_comparator = new HandComparator($hand_finder_1, $hand_finder_2);

        $this->assertEquals(1, $hand_comparator->did_player_1_win());
    }
}
