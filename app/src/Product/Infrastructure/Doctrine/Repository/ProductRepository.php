<?php
declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Repository;

use App\Cart\Infrastructure\Doctrine\Exception\TryingToAddAlreadyPersistedCartException;
use App\Cart\Infrastructure\Doctrine\Exception\TryingToUpdateNotPersistedCartException;
use App\Product\Infrastructure\Doctrine\Exception\ProductRepositoryException;
use App\Product\Infrastructure\Doctrine\Exception\TryingToAddAlreadyPersistedProductException;
use App\Product\Infrastructure\Doctrine\Exception\TryingToUpdateNotPersistedProductException;
use App\Shared\Domain\ValueObject\Uuid;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * Doctrine implementation of repositiory for Domain Product
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function addProduct(Product $product): void
    {
        $isPersisted = UnitOfWork::STATE_MANAGED === $this->_em->getUnitOfWork()->getEntityState($product);

        if ($product->hasId() || $isPersisted) {
            throw new TryingToAddAlreadyPersistedProductException(sprintf("Product is already persisted or has an database ID. Try using method ProductRepository::updateCart"));
        }

        try {
            $this->_em->persist($product);
            $this->_em->flush($product);
        } catch (\Exception $e) {
            throw ProductRepositoryException::create("Product adding unsuccessful. See previous exception for more details", $e);
        }
    }

    /**
     * It will also delete many-to-many relations to cart from cart_products due to onDelete=CASCADE set on many-to-many relation joins in the mapping
     *
     * @param Product $product
     * @return void
     */
    public function removeProduct(Product $product): void
    {
        if (!$product->hasId()) {
            throw new TryingToUpdateNotPersistedProductException(sprintf("Product is not persisted or does not have DB ID. Try using method ProductRepository::createNew"));
        }

        try {
            $this->_em->remove($product);
            $this->_em->flush($product);
        } catch (\Exception $e) {
            throw ProductRepositoryException::create("Product removing unsuccessful. See previous exception for more details", $e);
        }
    }

    public function updateProduct(Product $product): void
    {
        if (!$product->hasId()) {
            throw new TryingToUpdateNotPersistedProductException(sprintf("Product is not persisted or does not have DB ID."));
        }

        try {
            $this->_em->flush($product);
        } catch (\Exception $e) {
            throw ProductRepositoryException::create("Product updating unsuccessful. See previous exception for more details", $e);
        }
    }

    public function findProductByUuid(Uuid $uuid): ?Product
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where("p.uuid = :uuid");
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

    public function findProductById(int $id): ?Product
    {
        return $this->find($id);
    }

    public function findProductByName(string $name): ?Product
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where("p.name = :name");
        $qb->setParameter('name', $name);
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


    public function getProductsList(int $offset, int $limit): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        $products = $qb->getQuery()->execute();

        return $products;
    }

}