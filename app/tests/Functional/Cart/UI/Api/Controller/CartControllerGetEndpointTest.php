<?php
declare(strict_types=1);

namespace App\Tests\Functional\Product\UI\Api\Controller;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepositoryInterface;
use App\DataFixtures\ProductFixtures;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartControlleGetEndpointTest extends WebTestCase
{
    use ProductTestHelper;

    private KernelBrowser $httpClient;

    private string $uri = '/api/carts/%s';
    private CartRepositoryInterface $cartRepository;
    private ProductRepositoryInterface $productRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $kernel = static::bootKernel();
        $this->cartRepository = self::getContainer()->get(CartRepositoryInterface::class);
        $this->productRepository = self::getContainer()->get(ProductRepositoryInterface::class);
    }

    public function testSuccessfulHttpRequest(): void
    {
        // Given
        $newCart = Cart::createNew();
        $this->cartRepository->createNewCart($newCart);
        $uuidString = $newCart->getUuid();
        $newProduct = Product::createNew($this->generateValidProductName(), $this->generateValidPrice());
        $this->productRepository->addProduct($newProduct);
        $data = ['productUuid' => $newProduct->getUuid()->toString()];
        $newCart->addProduct($newProduct);
        $this->cartRepository->updateCart($newCart);

        $data = ['productUuid' => $newProduct->getUuid()->toString()];

        // When
        $this->client->request('GET', sprintf($this->uri, $uuidString), $data);
        $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->carts));
        $this->assertTrue(count($responseData->carts) == 1);
        $this->assertTrue(isset($responseData->carts[0]->products));
        $this->assertCount(1, $responseData->carts[0]->products);
    }


}