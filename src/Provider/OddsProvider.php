<?php

namespace SDM\Enetpulse\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use SDM\Enetpulse\Model\Odds;

class OddsProvider extends AbstractProvider
{
    /**
     * @param int $participantId
     *
     * @return Odds[]
     */
    public function getOddsByEventParticipantId(int $participantId): array
    {
        $qb = $this->queryBuilder($participantId);

        return $this->createObject($qb);
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return Odds[]
     */
    private function createObject(QueryBuilder $qb): array
    {
        $odds = [];
        foreach ($this->fetchObjects($qb) as $item) {
            $this->createOdds($item, $odds);
        }

        return $odds;
    }

    /**
     * @param \stdClass $object
     * @param Odds[]    $odds
     */
    private function createOdds(\stdClass $object, array &$odds): void
    {
        $id = $object->o_id;
        if (isset($odds[$id])) {
            $item = $odds[$id];
        } else {
            $item = new Odds(
                $object->o_id,
                $object->o_scope,
                $object->o_subtype
            );
        }

        $item->addOffer(new Odds\Offer(
            $object->b_id,
            new Odds\Provider(
                $object->op_id,
                $object->op_name,
                $object->op_url,
                $object->country_name,
                $this->createBool($object->op_bookmaker)
            ),
            $object->b_odds,
            $object->b_odds_old,
            $object->b_volume,
            $object->b_currency,
            $object->b_couponkey
        ));
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

        $qb
            ->andWhere(
                $qb->expr()->eq('op.active', ':op_active'),
                $qb->expr()->eq('b.active', ':b_active')
            )
        ;

        $qb
            ->andWhere(
                $qb->expr()->eq('o.iparam', ':participantId'),
                $qb->expr()->eq('o.type', ':type')
            )
            ->setParameter(':participantId', $participantId)
            ->setParameter(':type', '1x2')
        ;

        return $qb;
    }
}
