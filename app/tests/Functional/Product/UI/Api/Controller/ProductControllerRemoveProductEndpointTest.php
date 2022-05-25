<?php
declare(strict_types=1);

namespace App\Tests\Functional\Product\UI\Api\Controller;

use App\DataFixtures\ProductFixtures;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductControllerRemoveProductEndpointTest extends WebTestCase
{
    use ProductTestHelper;

    private KernelBrowser $httpClient;

    protected ProductRepositoryInterface $productRepository;

    private string $uri = '/api/products/%s';

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->productRepository = static::getContainer()->get(ProductRepositoryInterface::class);
    }

    public function testSuccessfulRemoveProductHttpRequest(): void
    {
        // Given
        $steveJobsProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[1]['name']);
        $uuid = $steveJobsProduct->getUuid()->toString();

        // When
       $this->client->request('DELETE', sprintf($this->uri, $uuid), []);
       $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testHttpBadRequestRemovalWhenProductDoesNotExist(): void
    {
        // Given
        $uuid = Uuid::random()->toString();

        // When
        $this->client->request('DELETE', sprintf($this->uri, $uuid), []);
        $response = $this->client->getResponse();

        // Then
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }


}