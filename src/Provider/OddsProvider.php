<?php

declare(strict_types=1);

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Odds;
use SDM\Enetpulse\Utils\Utils;

class OddsProvider extends AbstractProvider
{
    private static $odds = [];

    /**
     * @param int $participantId
     *
     * @return Odds[]
     */
    public function getOddsByEventParticipantId(int $participantId): array
    {
        $qb = $this->queryBuilder($participantId);

        return $this->build($qb);
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return Odds[]
     */
    private function build(QueryBuilder $qb): array
    {
        foreach ($this->fetchObjects($qb) as $item) {
            $this->createObject($item);
        }

        $odds = self::$odds;
        self::$odds = [];

        return array_values($odds);
    }

    /**
     * @param \stdClass $object
     */
    private function createObject(\stdClass $object): void
    {
        $id = $object->o_scope . $object->o_subtype;
        if (isset(self::$odds[$id])) {
            $item = self::$odds[$id];
        } else {
            $item = new Odds(
                (int) $object->o_id,
                $object->o_scope,
                $object->o_subtype
            );
        }

        $item->addOffer(new Odds\Offer(
            (int) $object->b_id,
            new Odds\Provider(
                (int) $object->op_id,
                $object->op_name,
                $object->op_url,
                $object->country_name,
                Utils::createBool($object->op_bookmaker)
            ),
            (float) $object->b_odds,
            (float) $object->b_odds_old,
            (int) $object->b_volume,
            $object->b_currency,
            $object->b_couponkey
        ));

        self::$odds[$id] = $item;
    }

    protected function queryBuilder(int $participantId): QueryBuilder
    {
        $qb = $this->getBuilder();
        $qb
            // Outcome
            ->from('outcome', 'o')
            ->addSelect('o.id as o_id', 'o.scope as o_scope', 'o.subtype as o_subtype')
            // Bettingoffer
            ->innerJoin('o', 'bettingoffer', 'b', 'b.outcomeFK = o.id')
            ->addSelect('b.id as b_id', 'b.odds as b_odds', 'b.odds_old as b_odds_old', 'b.volume as b_volume', 'b.currency as b_currency', 'b.couponKey as b_couponkey')
            // Oddsprovider
            ->innerJoin('b', 'odds_provider', 'op', 'b.odds_providerFK = op.id')
            ->addSelect('op.id as op_id', 'op.name as op_name', 'op.url as op_url', 'op.bookmaker as op_bookmaker')
            // Country
            ->innerJoin('op', 'country', 'c', 'op.countryFK = c.id')
            ->addSelect('c.name as country_name')
        ;

        $this->removeDeleted($qb, ['o', 'b', 'op', 'c']);

        if ($providers = $this->configuration->getOddsProviders()) {
            $qb->andWhere($qb->expr()->in('op.id', $providers));
        }

        if ($countryNames = $this->configuration->getOddsCountryNames()) {
            $qb->andWhere($qb->expr()->in('c.name', array_map(function ($countryName) {
                return '"' . $countryName . '"';
            }, $countryNames)));
        }

        $qb
            ->andWhere(
                $qb->expr()->eq('op.active', ':op_active'),
                $qb->expr()->eq('b.active', ':b_active')
            )
        ;
        $qb->setParameter(':op_active', 'yes');
        $qb->setParameter(':b_active', 'yes');

        $qb->andWhere($qb->expr()->eq('o.iparam', ':participantId'))
            ->setParameter(':participantId', $participantId)
        ;

        return $qb;
    }
}
