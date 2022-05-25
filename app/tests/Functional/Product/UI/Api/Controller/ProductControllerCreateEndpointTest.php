<?php
declare(strict_types=1);

namespace App\Tests\Functional\Product\UI\Api\Controller;

use App\DataFixtures\ProductFixtures;
use App\Tests\Helper\ProductTestHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductControllerCreateEndpointTest extends WebTestCase
{
    use ProductTestHelper;

    private KernelBrowser $httpClient;

    private string $uri = '/api/products';

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testSuccessfulHttpRequest(): void
    {
        // Given
        $post = [
            'name' => $this->generateValidProductName(),
            'price' => $this->generateValidPrice()
        ];

        // When
       $this->client->request('POST', $this->uri, $post);
       $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->products));
        $this->assertTrue(count($responseData->products) == 1);
    }

    public function invalidProductDataProvider(): array
    {
        return [
            [$this->generateTooShortProductName(), $this->generateValidPrice()], // to short name, float valid price
            [$this->generateTooLongProductName(), $this->generateValidPrice()], // to long name, float valid price
            [$this->generateValidProductName(), $this->generatePriceGreaterThanMax()], // name valid, float price too high
        ];
    }

    public function testHttpBadRequestWhenPriceNotNumeric(): void
    {
        // Given
        $post = [
            'name' => $this->generateValidProductName(),
            'price' => 'ala ma kota'
        ];

        // When
        $this->client->request('POST', $this->uri, $post);
        $response = $this->client->getResponse();

        // Then
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->message));
    }

}