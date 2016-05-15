<?php

namespace BiteCodes\DoctrineFilterBundle\Repository;

use BiteCodes\DoctrineFilter\FilterBuilder;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class FilterableEntityRepository extends EntityRepository
{
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * Initializes a new <tt>EntityRepository</tt>.
     *
     * @param EntityManager $em The EntityManager to use.
     * @param ClassMetadata $class The class descriptor.
     */
    public function __construct($em, ClassMetadata $class, FilterBuilder $filterBuilder)
    {
        parent::__construct($em, $class);
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @param FilterInterface $filter
     * @param $searchParams
     * @return array
     */
    public function filter($filter, $searchParams = [])
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');

        return $this->filterBuilder
            ->setQueryBuilder($qb)
            ->setFilter($filter)
            ->getResult($searchParams);
    }

    /**
     * @param FilterInterface $filter
     * @param $searchParams
     * @param $page
     * @param $maxPerPage
     * @param Pagerfanta $pagerfanta
     * @return array
     */
    public function paginate($filter, $searchParams, $page, $maxPerPage, &$pagerfanta = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');

        $query = $this->filterBuilder
            ->setQueryBuilder($qb)
            ->setFilter($filter)
            ->buildQuery($searchParams)
            ->getQuery();

        $adapter = new DoctrineORMAdapter($query);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setAllowOutOfRangePages(true)
            ->setMaxPerPage($maxPerPage)
            ->setCurrentPage($page);

        return iterator_to_array($pagerfanta->getCurrentPageResults());
    }
}