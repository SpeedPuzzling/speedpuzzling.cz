<?php

declare(strict_types=1);

namespace SpeedPuzzling\Web\Results;

readonly final class PuzzleSolver
{
    public function __construct(
        public string $playerId,
        public string $playerName,
        public int $time,
    ) {
    }

    /**
     * @param array{
     *     player_id: string,
     *     player_name: string,
     *     time: int,
     * } $row
     */
    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            playerId: $row['player_id'],
            playerName: $row['player_name'],
            time: $row['time'],
        );
    }
}
