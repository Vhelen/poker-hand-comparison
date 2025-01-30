<?php

namespace App\Tests\Service;

use App\Service\HandFinder;
use PHPUnit\Framework\TestCase;

class HandFinderTest extends TestCase
{
    public function test_royal_flush()
    {
        $hand = ['10H', 'JH', 'QH', 'KH', 'AH']; // Royal Flush
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('royal_flush', $hand_finder->get_type_hand());
    }

    public function test_straight_flush()
    {
        $hand = ['9H', '10H', 'JH', 'QH', 'KH']; // Straight Flush
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('straight_flush', $hand_finder->get_type_hand());
    }

    public function test_flush()
    {
        $hand = ['2H', '5H', '8H', 'KH', 'AH']; // Flush
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('flush', $hand_finder->get_type_hand());
    }


    /**
     * Straight test case because edgy with low ace.
     * @group straight
     * @return void
     */
    public function test_straight()
    {
        $hand = ['3H', '4D', '5S', '6C', '7H']; // Straight
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('straight', $hand_finder->get_type_hand());

        $hand = ['AH', '2D', '3S', '4C', '5H']; // Straight low ace
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('straight', $hand_finder->get_type_hand());

        $hand = ['AH', 'AD', '3S', '4C', '5H']; // Not Straight low ace
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertNotEquals('straight', $hand_finder->get_type_hand());

        $hand = ['10H', 'JS', 'QS', 'KC', 'AH']; // Straight high ace
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('straight', $hand_finder->get_type_hand());
    }

    public function test_four_of_a_kind()
    {
        $hand = ['5H', '5D', '5S', '5C', 'KH']; // Four of a Kind
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('four_of_a_kind', $hand_finder->get_type_hand());
    }

    public function test_full_house()
    {
        $hand = ['3H', '3D', '3S', '5C', '5H']; // Full House
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('full_house', $hand_finder->get_type_hand());
    }

    public function test_three_of_a_kind()
    {
        $hand = ['7H', '7D', '7S', '4C', '9H']; // Three of a Kind
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('three_of_a_kind', $hand_finder->get_type_hand());
    }

    public function test_two_pair()
    {
        $hand = ['3H', '3D', '9S', '9C', 'KH']; // Two Pair
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('two_pair', $hand_finder->get_type_hand());
    }

    public function test_pair()
    {
        $hand = ['4H', '4D', '7S', '9C', 'KH']; // Pair
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('pair', $hand_finder->get_type_hand());
    }

    public function test_high_card()
    {
        $hand = ['2H', '4D', '7S', '9C', 'KH']; // High Card
        shuffle($hand); // shuffle hand

        $hand_finder = new HandFinder($hand);
        $this->assertEquals('high_card', $hand_finder->get_type_hand());
    }
}

