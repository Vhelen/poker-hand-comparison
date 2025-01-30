<?php

namespace App\Tests\Service;

use App\Service\CardChecker;
use PHPUnit\Framework\TestCase;

class CardCheckerTest extends TestCase
{
    public function test_valid_hands(): void
    {
        $hand_player_1 = '2C 7D 5H AS 4C';
        $hand_player_2 = '3S 8H 9D TC JD';

        $card_checker = new CardChecker($hand_player_1, $hand_player_2);

        $this->assertEquals(['2C', '7D', '5H', 'AS', '4C'], $card_checker->get_hand_player_1());
        $this->assertEquals(['3S', '8H', '9D', 'TC', 'JD'], $card_checker->get_hand_player_2());
    }

    public function test_invalid_hand_format_player_1(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Player 1: Invalid hand format');

        $hand_player_1 = '2C 7D 5H AS'; // Invalid format
        $hand_player_2 = '3S 8H 9D TC JD';

        new CardChecker($hand_player_1, $hand_player_2);
    }

    public function test_invalid_hand_format_player_2(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Player 2: Invalid hand format');

        $hand_player_1 = '2C 7D 5H AS 4C';
        $hand_player_2 = '3S 8H 9D TC'; // Invalid format

        new CardChecker($hand_player_1, $hand_player_2);
    }

    public function test_duplicate_cards(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Duplicate card in player 2 hand');

        $hand_player_1 = '2C 7D 5H AS 4C';
        $hand_player_2 = '4C 4C 9D TC JD'; // Duplicate card (4C)

        new CardChecker($hand_player_1, $hand_player_2);
    }
}
