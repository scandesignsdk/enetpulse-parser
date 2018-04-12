<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Utils;

use Doctrine\DBAL\Query\QueryBuilder;

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
}
