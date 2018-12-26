<?php


namespace Domain\Shared\Repository;

use Doctrine\ORM\EntityManager;

/**
 * Class AbstractRepository
 * @package Domain\Shared\Repository
 */
abstract class AbstractRepository
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * AbstractRepository constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveOne($object): bool
    {
        $this->manager->persist($object);
        $this->manager->flush();
        return true;
    }

    /**
     * @param array $objects
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMany(array $objects): bool
    {
        foreach ($objects as $object) {
            $this->manager->persist($object);
        }
        $this->manager->flush();
        return true;
    }

    /**
     * startTransaction
     */
    public function startTransaction(): void
    {
        $this->manager->beginTransaction();
    }

    /**
     * commitTransaction
     */
    public function commitTransaction(): void
    {
        $this->manager->commit();
    }

    /**
     * rollbackTransaction
     */
    public function rollbackTransaction(): void
    {
        $this->manager->rollback();
    }
}