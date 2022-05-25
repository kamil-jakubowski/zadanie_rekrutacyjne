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
class ProductControllerUpdateNameEndpointTest extends WebTestCase
{
    use ProductTestHelper;

    private KernelBrowser $httpClient;

    protected ProductRepositoryInterface $productRepository;

    private string $uri = '/api/products/%s/updatename';

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->productRepository = static::getContainer()->get(ProductRepositoryInterface::class);
    }

    public function testSuccessfulHttpRequest(): void
    {
        // Given
        $newName = $this->generateValidProductName();
        $body = [
            'name' => $newName
        ];
        $steveJobsProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[1]['name']);
        $uuid = $steveJobsProduct->getUuid()->toString();

        // When
       $this->client->request('PATCH', sprintf($this->uri, $uuid), $body);
       $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->products));
        $this->assertTrue(count($responseData->products) == 1);
        $this->assertEquals($responseData->products[0]->name, $newName);
    }

    public function testHttpBadRequestWhenProductDoesNotExist(): void
    {
        // Given
        $uuid = Uuid::random()->toString();

        // When
        $this->client->request('PATCH', sprintf($this->uri, $uuid), ['name' => $this->generateValidProductName()]);
        $response = $this->client->getResponse();

        // Then
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testHttpBadRequestWhenNameEmpty(): void
    {
        // Given
        $steveJobsProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[1]['name']);
        $uuid = $steveJobsProduct->getUuid()->toString();

        // When
        $this->client->request('PATCH', sprintf($this->uri, $uuid), ['name' => '']);
        $response = $this->client->getResponse();

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testHttpBadRequestWhenNameTooLong(): void
    {
        // Given
        $steveJobsProduct = $this->productRepository->findProductByName(ProductFixtures::PRODUCT_INFO[1]['name']);
        $uuid = $steveJobsProduct->getUuid()->toString();

        // When
        $this->client->request('PATCH', sprintf($this->uri, $uuid), ['name' => $this->generateTooLongProductName()]);
        $response = $this->client->getResponse();

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

}