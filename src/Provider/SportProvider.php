<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Sport;

class SportProvider extends AbstractProvider
{
    /**
     * @return Sport[]
     */
    public function getSports(): array
    {
        $sports = [];
        foreach ($this->fetchObjects($this->queryBuilder()) as $item) {
            $sports[] = $this->createObject($item);
        }

        return $sports;
    }

    public function getSportByName(string $name): ?Sport
    {
        $qb = $this->queryBuilder();
        $qb
            ->andWhere(
                $qb->expr()->eq('LOWER(sport.name)', ':name')
            )
        ;
        $qb->setParameter(':name', mb_strtolower($name));
        if ($result = $this->fetchSingle($qb)) {
            return $this->createObject($result);
        }

        return null;
    }

    private function createObject(\stdClass $data): Sport
    {
        return new Sport($data->id, $data->name);
    }

    protected function queryBuilder(): QueryBuilder
    {
        $qb = $this->getBuilder();
        $qb
            ->select(['sport.id', 'sport.name'])
            ->from('sport', 'sport')
        ;
        $this->removeDeleted($qb, ['sport']);

        return $qb;
    }
}
