<?php

declare(strict_types=1);

namespace SpeedPuzzling\Web\Query;

use Doctrine\DBAL\Connection;
use SpeedPuzzling\Web\Results\SolvedPuzzle;

readonly final class GetLastSolvedPuzzle
{
    public function __construct(
        private Connection $database,
    ) {
    }

    /**
     * @return array<SolvedPuzzle>
     */
    public function limit(int $limit): array
    {
        $query = <<<SQL
SELECT
    puzzle_solving_time.id as time_id,
    puzzle.id AS puzzle_id,
    puzzle.name AS puzzle_name,
    puzzle.alternative_name AS puzzle_alternative_name,
    puzzle.image AS puzzle_image,
    puzzle_solving_time.seconds_to_solve AS time,
    puzzle_solving_time.player_id AS player_id,
    player.name AS player_name,
    pieces_count,
    puzzle_solving_time.comment,
    manufacturer.name AS manufacturer_name,
    puzzle.identification_number AS puzzle_identification_number,
    puzzle_solving_time.tracked_at AS tracked_at,
    puzzle_solving_time.finished_puzzle_photo AS finished_puzzle_photo,
    puzzle_solving_time.team ->> 'team_id' AS team_id,
    CASE
        WHEN puzzle_solving_time.team IS NOT NULL THEN JSON_AGG(
            JSON_BUILD_OBJECT(
                'player_id', player_elem ->> 'player_id',
                'player_name', COALESCE(p.name, player_elem ->> 'player_name')
            )
        )
    END AS players
FROM puzzle_solving_time
INNER JOIN puzzle ON puzzle.id = puzzle_solving_time.puzzle_id
INNER JOIN player ON puzzle_solving_time.player_id = player.id
INNER JOIN manufacturer ON manufacturer.id = puzzle.manufacturer_id
LEFT JOIN LATERAL json_array_elements(puzzle_solving_time.team -> 'puzzlers') AS player_elem ON true
LEFT JOIN player p ON p.id = (player_elem ->> 'player_id')::UUID
WHERE
    player.name IS NOT NULL
GROUP BY puzzle_solving_time.id, puzzle.id, manufacturer.id, time, player.id
ORDER BY puzzle_solving_time.tracked_at DESC
LIMIT :limit
SQL;

        $data = $this->database
            ->executeQuery($query, [
                'limit' => $limit,
            ])
            ->fetchAllAssociative();

        return array_map(static function(array $row): SolvedPuzzle {
            /**
             * @var array{
             *     time_id: string,
             *     player_id: string,
             *     player_name: string,
             *     puzzle_id: string,
             *     puzzle_name: string,
             *     puzzle_alternative_name: null|string,
             *     manufacturer_name: string,
             *     puzzle_image: null|string,
             *     time: int,
             *     pieces_count: int,
             *     comment: null|string,
             *     tracked_at: string,
             *     finished_puzzle_photo: null|string,
             *     team_id: null|string,
             *     players: null|string,
             *     puzzle_identification_number: null|string,
             * } $row
             */

            return SolvedPuzzle::fromDatabaseRow($row);
        }, $data);
    }
}
