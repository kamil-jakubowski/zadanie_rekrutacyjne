<?php

namespace App\DataFixtures;

use App\Cart\Domain\CartRepositoryInterface;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    protected ProductRepositoryInterface $productRepository;

    public const PRODUCT_INFO = [
        ['name' => 'The Godfather', 'price' => 59.99],
        ['name' => 'Steve Jobs', 'price' => 49.95],
        ['name' => 'The Return of Sherlock Holmes ', 'price' => 39.99],
        ['name' => 'The Little Prince', 'price' => 29.99],
        ['name' => 'I Hate Myselfie', 'price' => 19.99],
        ['name' => 'The Trial ', 'price' => 9.99],
    ];

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (static::PRODUCT_INFO as $spec) {
            $product = Product::createNew($spec['name'], $spec['price']);
            $this->productRepository->addProduct($product);
        }
    }
}
