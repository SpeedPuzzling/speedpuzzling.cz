<?php

declare(strict_types=1);

namespace SpeedPuzzling\Web\Results;

use DateTimeInterface;
use SpeedPuzzling\Web\Value\StopwatchStatus;

readonly final class StopwatchDetail
{
    public function __construct(
        public string $stopwatchId,
        public int $totalSeconds,
        public DateTimeInterface $lastStart,
        public null|DateTimeInterface $lastEnd,
        public StopwatchStatus $status,
    ) {
    }

    /**
     * @param array{
     *     stopwatch_id: string,
     *     total_seconds: null|int|float|string,
     *     last_start_time: null|string,
     *     last_end_time: null|string,
     *     status: string,
     * } $row
     */
    public static function fromDatabaseRow(array $row)
    {
        $lastStart = new \DateTimeImmutable();
        if ($row['last_start_time'] !== null) {
            $lastStart = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['last_start_time']);
        }

        $lastEnd = null;
        if ($row['last_end_time'] !== null) {
            $lastEnd = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['last_end_time']);
        }

        $totalSeconds = 0;
        if ($row['total_seconds'] !== null) {
            $totalSeconds = (int) $row['total_seconds'];
        }

        return new self(
            stopwatchId: $row['stopwatch_id'],
            totalSeconds: $totalSeconds,
            lastStart: $lastStart,
            lastEnd: $lastEnd,
            status: StopwatchStatus::from($row['status'])
        );
    }
}
