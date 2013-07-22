<?php
namespace Noodle\Repository;

use Doctrine\ORM\EntityRepository;

class Base extends EntityRepository
{

	function findModuleItems($orderElement = null, $orderDirection = null, $where = null)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('u')
		->from($this->getEntityName(), 'u');

		$qb->andWhere('u.parent_entity IS NULL');

		if(!$orderElement){
			$qb->orderBy('u.id', 'DESC');
		} elseif(!$orderElement->getOption('relationColumn')) {
			// classic easy sort in current table
			$qb->orderBy('u.'.$orderElement->getName(), $orderDirection);
		} else {
			// sort by relation
			$qb->leftJoin('u.'.$orderElement->getName(), 'r');
			$qb->orderBy('r.'.$orderElement->getOption('relationColumn'), $orderDirection);
		}

		// where filter
		if($where){
			$qb->andWhere($where);
		}

		//echo $qb->getQuery()->getSQL();

		return $qb->getQuery();
	}

}