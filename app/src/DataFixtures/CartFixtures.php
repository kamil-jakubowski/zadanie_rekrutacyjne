<?php

namespace App\DataFixtures;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture implements DependentFixtureInterface
{
    protected ProductRepositoryInterface $productRepository;

    protected CartRepositoryInterface $cartRepository;

    public function __construct(ProductRepositoryInterface $productRepo, CartRepositoryInterface $cartRepo)
    {
        $this->cartRepository = $cartRepo;
        $this->productRepository = $productRepo;
    }

    public function load(ObjectManager $manager): void
    {
        $product1 = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[4]['name']);
        $product2 = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[2]['name']);

        $cart1 = Cart::createNew([$product1, $product2]);
        $cart2 = Cart::createNew([]); // empty cart

        $this->cartRepository->createNewCart($cart1);
        $this->cartRepository->createNewCart($cart2);
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class
        ];
    }
}
