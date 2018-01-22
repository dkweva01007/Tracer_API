<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Configuration;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;

class DefaultRepository extends EntityRepository {

    public function my_findBy($table, $orderby = null, $limit = null, $offset = null) {
        $param = array();
        $txt = "(";
        $queryBuilder = $this->createQueryBuilder('a')
                ->select('a');
        $j = 0;
        if (sizeof($table) > 0) {
            foreach ($table as $key => $value) {
                if ($j > 0) {
                    $txt .= " AND ( ";
                }
                if (is_array($value)) {
                    for ($i = 0; $i < sizeof($value); $i++) {
                        switch ($key) {
                            case "created":
                            case "updated":
                                if (sizeof($value) > 1) {
                                    if (($i + 1) % 2 != 0) {
                                        if ($i == 0)
                                            $txt .= 'a.' . $key . ' >= :' . $key . $i;
                                        else
                                            $txt .= ' OR a.' . $key . ' >= :' . $key . $i;
                                    } else
                                        $txt .= ' AND a.' . $key . ' <= :' . $key . $i;
                                    $tmp = \DateTime::createFromFormat('d/m/Y', $value[$i]);
                                    if ($tmp) {
                                        $value[$i] = $tmp->format('Y-m-d');
                                    }
                                } else {
                                    if ($i == 0)
                                        $txt .= 'a.' . $key . ' >= :' . $key . $i;
                                    else
                                        $txt .= ' OR a.' . $key . ' >= :' . $key . $i;
                                    $tmp = \DateTime::createFromFormat('d/m/Y', $value[$i]);
                                    if ($tmp) {
                                        $value[$i] = $tmp->format('Y-m-d');
                                    }
                                }
                                break;
                            case (preg_match('/^r_/', $key) ? true : false):
                                substr($key, 0, 6);
                                if ($i == 0)
                                    $txt .= ' REGEXP(a.' . substr($key, 2, strlen($key)) . ', :' . $key . $i . ') = 1';
                                else
                                    $txt .= ' OR REGEXP(a.' . substr($key, 2, strlen($key)) . ', :' . $key . $i . ') = 1';
                                break;
                            default :
                                if ($i == 0)
                                    $txt .= 'a.' . $key . ' = :' . $key . $i;
                                else
                                    $txt .= ' OR a.' . $key . ' = :' . $key . $i;
                                break;
                        }
                        $queryBuilder->setParameter($key . $i, $value[$i]);
                    }
                    $j++;
                    $txt .= ")";
                } else {
                    switch ($key) {
                        case "created":
                        case "updated":
                            $txt .= 'a.' . $key . ' >= :' . $key;
                            $tmp = \DateTime::createFromFormat('d/m/Y', $value);
                            if ($tmp) {
                                $value = $tmp->format('Y-m-d');
                            }
                            break;
                        case (preg_match('/^r_/', $key) ? true : false):
                            substr($key, 0, 6);
                            $txt .= 'REGEXP(a.' . substr($key, 2, strlen($key)) . ', :' . $key . ') = 1';
                            break;
                        default :
                            $txt .= 'a.' . $key . ' = :' . $key;
                            break;
                    }
                    $queryBuilder->setParameter($key, $value);
                    $j++;
                    $txt .= ")";
                }
            }
            $queryBuilder->where($txt);
        }
        $queryBuilder->orderBy('a.id', 'DESC');

        foreach ((array) $orderby as $key => $value) {
            $queryBuilder->orderBy("a." . $key, $value);
        }
        if ($limit || $offset) {
            $result = $this->my_countBy($table)[0];
            $result['entities'] = $queryBuilder->getQuery()
                    ->setMaxResults($limit)
                    ->setFirstResult($offset)
                    ->getResult();
            $result['numberOfRecords'] = intval($result['numberOfRecords']);
            return $result;
        } else {
            return $queryBuilder->getQuery()
                            ->setMaxResults($limit)
                            ->setFirstResult($offset)
                            ->getResult();
        }
    }
}
