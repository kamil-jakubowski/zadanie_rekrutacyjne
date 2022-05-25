<?php
declare(strict_types=1);

namespace App\Tests\Functional\Product\UI\Api\Controller;

use App\DataFixtures\ProductFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductControllerGetListEndpointTest extends WebTestCase
{
    private KernelBrowser $httpClient;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testSuccessfulHttpRequest(): void
    {
        // Given
        $uri = '/api/products?items=3&page=1';

        // When
       $this->client->request('GET', $uri);
       $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->products));
        $this->assertTrue(count($responseData->products) == 3);
        $this->assertTrue(isset($responseData->meta) && isset($responseData->meta->quantityOfAll));
        $this->assertTrue($responseData->meta->quantityOfAll == count(ProductFixtures::PRODUCT_INFO));
    }

    public function testHttpBadRequestWhenPageParameterIsNegative(): void
    {
        // Given
        $uri = '/api/products?items=3&page=-1';

        // When
        $this->client->request('GET', $uri);
        $response = $this->client->getResponse();

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testHttpBadRequestWhenPageParameterisNotNumeric(): void
    {
        // Given
        $uri = '/api/products?items=3&page=alamakota';

        // When
        $this->client->request('GET', $uri);

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testHttpBadRequestItemsAreMoreThanMax(): void
    {
        // Given
        $uri = '/api/products?items=4';

        // When
        $this->client->request('GET', $uri);

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testHttpBadRequestWhenItemsAreNegative(): void
    {
        // Given
        $uri = '/api/products?items=-1';

        // When
        $this->client->request('GET', $uri);

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testHttpBadRequestWhenItemsAreNotNumeric(): void
    {
        // Given
        $uri = '/api/products?items=sdfsdf';

        // When
        $this->client->request('GET', $uri);

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}