<?php
namespace Nnmer\UtilsBundle\Lib;


//use Doctrine\DBAL\Query\Expression\CompositeExpression;
//use Doctrine\DBAL\Query\QueryBuilder;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\ORM\QueryBuilder;

class Doctrine {
    /**
     * Taken from https://gist.github.com/jgornick/8671644
     *
     * Recursively takes the specified criteria and adds too the expression.
     *
     * The criteria is defined in an array notation where each item in the list
     * represents a comparison <fieldName, operator, value>. The operator maps to
     * comparison methods located in ExpressionBuilder. The key in the array can
     * be used to identify grouping of comparisons.
     *
     * @example
     * $criteria = array(
     *      'or' => array(
     *          array('field1', 'like', '%field1Value%'),
     *          array('field2', 'like', '%field2Value%')
     *      ),
     *      'and' => array(
     *          array('field3', 'eq', 3),
     *          array('field4', 'eq', 'four')
     *      ),
     *      array('field5', 'neq', 5)
     * );
     *
     * $qb = new QueryBuilder();
     * addCriteria($qb, $qb->expr()->andX(), $criteria);
     * echo $qb->getSQL();
     *
     * // Result:
     * // SELECT *
     * // FROM tableName
     * // WHERE ((field1 LIKE '%field1Value%') OR (field2 LIKE '%field2Value%'))
     * // AND ((field3 = '3') AND (field4 = 'four'))
     * // AND (field5 <> '5')
     *
     * @param QueryBuilder $qb
     * @param CompositeExpression $expr
     * @param array $criteria
     */
    static function addCriteria(QueryBuilder $qb, $expr, array $criteria)
    {
        if (count($criteria)) {
//            var_dump($criteria);
            foreach ($criteria as $element) {
                foreach ($element as $expression => $comparison) {
                    if (!is_array($comparison)){
                        $comparison = $element;
                    }
                    if ($expression>0) continue;
//                    echo
//                        "Elem:".((is_array($element))?print_r($element,true):$element).'--'.
//                        "Expr:".((is_array($expression))?print_r($expression,true):$expression).'--'.
//                        "Comp:".((is_array($comparison))?print_r($comparison,true):$comparison).'--'.
//                        "<br>";
//break;
//                    echo "<br><br>";
//                    echo($expression);
//                    echo(print_r($comparison,true));
//                    if ($comparison)

                    if ($expression === 'or') {
                        $expr->add(Doctrine::addCriteria(
                            $qb,
                            $qb->expr()->orX(),
                            $comparison
                        ));
                    } else if ($expression === 'and') {
                        $expr->add(Doctrine::addCriteria(
                            $qb,
                            $qb->expr()->andX(),
                            $comparison
                        ));
                    } else {
//                        print_r($comparison);
                        if (count($comparison)==3) {
                            list($field, $operator, $value) = $comparison;
                        }elseif (count($comparison)==2) {
                            list($field, $operator) = $comparison;
                        }

                        if ($operator == 'in')
                            $expr->add($qb->expr()->{$operator}($field, $value));
                        elseif(in_array($operator,['isNull','isNotNull'])) {
                            $expr->add($qb->expr()->{$operator}($field));
                        }else
                            $expr->add($qb->expr()->{$operator}($field, $qb->expr()->literal($value)));
                    }

                }
//                echo $qb->getDql()."<br><br>";
            }
        }

        return $expr;
    }
} 