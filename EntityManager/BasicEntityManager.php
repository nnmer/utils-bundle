<?php
namespace Nnmer\UtilsBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Nnmer\UtilsBundle\Lib\Doctrine;
use Nnmer\UtilsBundle\Lib\Strings;

abstract class BasicEntityManager
{
    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    public function __construct($class, EntityManager $entityManager)
    {
        $this->entityClassName  = $class;
        $this->entityManager    = $entityManager;
        $this->em               = $entityManager;
        $this->repository       = $entityManager->getRepository($class);
    }

    /**
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * Build the search for each field for entered terms
     * the terms could be separated by the space
     *
     * fields should be specified in array
     *
     * if DQL is null then return the built criteria. If criteria was already prebuild in parent method -
     * when call this method need to put that criteria as 3rd param
     *
     * @param QueryBuilder|null $dql
     * @param                   $fields
     * @param                   $term
     * @param array             $criteria
     *
     * @return array|QueryBuilder
     */
    public function buildSearchDql(QueryBuilder $dql=null, $fields, $term, &$criteria = array()){

        $terms = Strings::parseStringSequence($term," ");

        if (count($fields)==0 || empty($terms)){
            return $dql;
        }

        $tmpArray = array();
        if (count($terms)>0){
            foreach($terms as $term){
                $tmp = array();
                foreach ($fields as $field) {
                    $tmp['or'][] = array("$field","like","%$term%");
                }
                if (count($tmp)>0)
                    $tmpArray['and'][] = $tmp;
            }
        }

        if (count($tmpArray)>0){
            $criteria[] = $tmpArray;
        }

        if ($dql === null){
            return $criteria;
        }

        if (count($criteria)>0){
            $expr = Doctrine::addCriteria($dql,$dql->expr()->andX(),$criteria);
            $dql->andWhere($expr);
        }

        return $dql;
    }
}