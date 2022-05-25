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
class CartControllerCreateEndpointTest extends WebTestCase
{
    use ProductTestHelper;

    private KernelBrowser $httpClient;

    private string $uri = '/api/carts';

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testSuccessfulHttpRequest(): void
    {
        // Given
        $post = [];

        // When
       $this->client->request('POST', $this->uri, $post);
       $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode($response->getContent());
        $this->assertTrue(isset($responseData->carts));
        $this->assertTrue(count($responseData->carts) == 1);
    }


}