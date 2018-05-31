<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Utils;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Event;

class Utils
{
    /**
     * @codeCoverageIgnore
     *
     * @param QueryBuilder $builder
     */
    public static function queryDebugger(QueryBuilder $builder): void
    {
        $runable = $builder->getSQL();
        if ($builder->getParameters()) {
            foreach ($builder->getParameters() as $key => $item) {
                $runable = str_replace($key, \is_string($item) ? '"' . $item . '"' : $item, $runable);
            }
        }

        if (\function_exists('dump')) {
            dump($builder->getSQL(), $builder->getParameters(), $runable);
            exit;
        }

        var_dump($builder->getSQL(), $builder->getParameters(), $runable);
        exit;
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public static function createBool($item): bool
    {
        if (\is_bool($item)) {
            return $item;
        }

        if (\is_string($item) && \in_array(mb_strtolower($item), ['yes', 'y', '1'], true)) {
            return true;
        }

        if (\is_int($item)) {
            return 0 !== $item;
        }

        return filter_var($item, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return \DateTime
     */
    public static function getToday(): \DateTime
    {
        if (isset($_ENV['MOCK_TODAY'])) {
            [$year, $month, $day] = explode('-', $_ENV['MOCK_TODAY']);

            return (new \DateTime())->setDate((int)$year, (int)$month, (int)$day);
        }

        return new \DateTime();
    }

    /**
     * @param Event[] $events
     * @param bool $removeEventsNoOffers
     *
     * @return Event\TournamentEvents[]
     */
    public static function groupEventsByTournament(array $events, $removeEventsNoOffers = true): array
    {
        /** @var Event\TournamentEvents[] $stages */
        $stages = [];
        foreach ($events as $event) {
            if ($removeEventsNoOffers && count($event->getOdds()) <= 0) {
                continue;
            }

            $tid = $event->getStage()->getId();
            if (!isset($stages[$tid])) {
                $stages[$tid] = new Event\TournamentEvents($event->getStage());
            }

            $stages[$tid]->addEvent($event);
        }

        return $stages;
    }
}
