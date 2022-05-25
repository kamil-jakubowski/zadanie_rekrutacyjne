<?php
declare(strict_types=1);

namespace App\Product\Application\Query\Product;

use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\CQRS\Query\InvalidCommandArgumentException;
use App\Shared\CQRS\Query\InvalidQueryArgumentException;
use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use App\Shared\Domain\ValueObject\Uuid;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductHandler implements ProductHandlerInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(ProductQuery $query): ?ProductViewModel
    {
        $uuidString = $query->getUuid();
        try {
            $uuid = Uuid::fromString($uuidString);
        } catch(InvalidUuidException $e) {
            throw new InvalidQueryArgumentException(sprintf("Uuid given is not valid uuid string, given %s", $uuidString));
        }

        $product = $this->productRepository->findProductByUuid($uuid);
        return ($product ? ProductViewModel::createFromDomainProduct($product) : null);
    }
}