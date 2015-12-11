<?php
namespace Nnmer\UtilsBundle\Lib;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Pagination {
    /**
     * Clones a query.
     *
     * @param QueryBuilder $query The query.
     *
     * @return QueryBuilder The cloned query.
     */
    public static function cloneQuery($query)
    {
        /* @var $cloneQuery QueryBuilder */
        $cloneQuery = clone $query;

        $cloneQuery->setParameters(clone $query->getParameters());

        return $cloneQuery;
    }

    /**
     * @param QueryBuilder $dql
     * @param null $limit
     * @param int $offset 0
     * @param null $page
     * @return array [
     *                  'total' => total number of records
     *                  'query' => QueryBuilder
     *              ]
     */
    public static function paginateResponse(QueryBuilder &$dql, $limit = null, $offset = 0, $page = null)
    {
        if ($page !== null) {
            $offset = ($page !== null && $limit !== null) ? $limit * ($page - 1) : null;
        }

        $clone = Pagination::cloneQuery($dql);
        $paginator = new Paginator($dql);
        $total = $paginator->count();

        if ($limit !== null && $offset !== null)
            $dql
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        $fromAlias = current($dql->getRootAliases());
        $result = $dql->select("DISTINCT $fromAlias.id")->getQuery()->getArrayResult();
        $ids = array_map('current', $result);

        if(count($ids)>0) {
            $clone->where($clone->expr()->in("$fromAlias.id", $ids));
        }else{
            $clone->where("1=0");
        }


        return array(
            'total' => $total,
            'query' => $clone
        );
    }
} 