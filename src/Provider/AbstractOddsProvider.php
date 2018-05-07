<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Odds;
use SDM\Enetpulse\Utils\Utils;

abstract class AbstractOddsProvider extends AbstractProvider
{
    private static $odds = [];

    /**
     * @param QueryBuilder $qb
     *
     * @return Odds[]
     */
    protected function build(QueryBuilder $qb): array
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
    protected function createObject(\stdClass $object): void
    {
        $id = $object->o_type . $object->o_scope . $object->o_subtype . $object->o_dparam1 . $object->o_dparam2 . $object->o_sparam . $object->o_iparam1 . $object->o_iparam2;
        if (isset(self::$odds[$id])) {
            $item = self::$odds[$id];
        } else {
            $item = new Odds(
                (int)$object->o_id,
                $object->o_type,
                $object->o_scope,
                $object->o_subtype,
                (float)$object->o_dparam1,
                (float)$object->o_dparam2,
                $object->o_sparam,
                $object->o_iparam1,
                $object->o_iparam2
            );
        }

        $item->addOffer(new Odds\Offer(
            (int)$object->b_id,
            new Odds\Provider(
                (int)$object->op_id,
                $object->op_name,
                $object->op_url,
                $object->country_name,
                Utils::createBool($object->op_bookmaker)
            ),
            (float)$object->b_odds,
            (float)$object->b_odds_old,
            (int)$object->b_volume,
            $object->b_currency,
            $object->b_couponkey
        ));

        self::$odds[$id] = $item;
    }

    protected function makeQueryBuilder(): QueryBuilder
    {
        $qb = $this->getBuilder();
        $qb
            // Outcome
            ->from('outcome', 'o')
            ->addSelect(
                'o.id as o_id',
                'o.type as o_type',
                'o.scope as o_scope',
                'o.subtype as o_subtype',
                'o.dparam as o_dparam1',
                'o.dparam2 as o_dparam2',
                'o.sparam as o_sparam',
                'o.iparam as o_iparam1',
                'o.iparam2 as o_iparam2'
            )
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

        if ($providers = $this->configuration->getOddsProviders()) {
            $qb->andWhere($qb->expr()->in('op.id', $providers));
        }

        if ($countryNames = $this->configuration->getOddsCountryNames()) {
            $qb->andWhere($qb->expr()->in('c.name', array_map(function ($countryName) {
                return '"' . $countryName . '"';
            }, $countryNames)));
        }

        $qb->andWhere(
            $qb->expr()->eq('op.active', ':op_active'),
            $qb->expr()->eq('b.active', ':b_active')
        );
        $qb->setParameter(':op_active', 'yes');
        $qb->setParameter(':b_active', 'yes');

        $this->removeDeleted($qb, ['o', 'b', 'op', 'c']);
        return $qb;
    }
}
