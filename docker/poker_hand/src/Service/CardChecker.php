<?php

namespace App\Service;


class CardChecker
{
    // regex hand format (ex: '2C 7D 5H AS 4C')
    const HAND_REGEX = '/^([2-9TJQKA][SHDC]\s){4}([2-9TJQKA][SHDC])$/';

    private array $hand_player_1;
    private array $hand_player_2;

    public function __construct(string $hand_player_1, string $hand_player_2)
    {
        // Check for hand format
        if(!$this->check_hand_format($hand_player_1)) throw new \Exception('Player 1: Invalid hand format');
        if(!$this->check_hand_format($hand_player_2)) throw new \Exception('Player 2: Invalid hand format');

        // convert hand string to array
        $this->hand_player_1 = $this->hand_to_array($hand_player_1);
        $this->hand_player_2 = $this->hand_to_array($hand_player_2);

        $this->check_for_duplicate();
    }

    /**
     * Return true if hand has the rigth format, else return false.
     *
     * @param string $hand
     *
     * @return bool
     */
    private function check_hand_format(string $hand): bool
    {
        if(preg_match(self::HAND_REGEX, $hand))
        {
            return true;
        }

        return false;
    }

    /**
     * Return hand converted to array.
     *
     * @param string $hand
     *
     * @return array
     */
    private function hand_to_array(String $hand): array
    {
        return explode(' ', $hand);
    }

    /**
     * Return true if players hand have no duplicate card.
     *
     * @return bool
     */
    private function check_for_duplicate(): bool
    {
        if (count($this->hand_player_1) !== count(array_unique($this->hand_player_1)))
        {
            throw new \Exception('Duplicate card in player 1 hand');
        }

        if (count($this->hand_player_2) !== count(array_unique($this->hand_player_2)))
        {
            throw new \Exception('Duplicate card in player 2 hand');
        }

        return false;
    }

    /**
     * Return player one hand.
     *
     * @return array
     */
    public function get_hand_player_1(): array
    {
        return $this->hand_player_1;
    }

    /**
     * Return player two hand.
     *
     * @return array
     */
    public function get_hand_player_2(): array
    {
        return $this->hand_player_2;
    }
}