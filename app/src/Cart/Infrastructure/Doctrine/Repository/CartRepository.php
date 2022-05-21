<?php
declare(strict_types=1);

namespace App\Cart\Infrastructure\Doctrine\Repository;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\Cart\Infrastructure\Doctrine\Exception\CartRepositoryException;
use App\Cart\Infrastructure\Doctrine\Exception\TryingToAddAlreadyPersistedCartException;
use App\Cart\Infrastructure\Doctrine\Exception\TryingToUpdateNotPersistedCartException;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Doctrine implementation of repositiory for Domain Cart
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function createNewCart(Cart $cart): void
    {
        $isPersisted = UnitOfWork::STATE_MANAGED === $this->_em->getUnitOfWork()->getEntityState($cart);

        if ($cart->hasId() || $isPersisted) {
            throw new TryingToAddAlreadyPersistedCartException(sprintf("Cart is already persisted or has an database ID. Try using method CartRepository::updateCart"));
        }

        try {
            $this->_em->persist($cart);
            $this->_em->flush($cart);
        } catch (\Exception $e) {
            throw CartRepositoryException::create("Cart adding unsuccessful. See previous exception for more details", $e);
        }
    }

    public function updateCart(Cart $cart): void
    {
        if (!$cart->hasId()) {
            throw new TryingToUpdateNotPersistedCartException(sprintf("Cart is not persisted or does not have DB ID. Try using method CartRepository::createNewCart"));
        }

        try {
            $this->_em->flush($cart);
        } catch (\Exception $e) {
            throw CartRepositoryException::create("Cart updating unsuccessful. See previous exception for more details", $e);
        }
    }

    public function removeCart(Cart $cart): void
    {
        if (!$cart->hasId()) {
            throw new TryingToUpdateNotPersistedCartException(sprintf("Cart is not persisted or does not have DB ID. Try using method CartRepository::createNewCart"));
        }

        try {
            $this->_em->remove($cart);
            $this->_em->flush($cart);
        } catch (\Exception $e) {
            throw CartRepositoryException::create("Cart removing unsuccessful. See previous exception for more details", $e);
        }
    }

    public function findCartByUuid(Uuid $uuid): ?Cart
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where("c.uuid = :uuid");
        $qb->setParameter('uuid', $uuid->toString());
        $qb->setMaxResults(1);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            // something really wrong, why we have two results with same uuid?
            // throwing futher
            throw $e;
        }
    }
}