<?php
declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Traits;

use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    //not really anything to test, only private/protected methods
    //this trait should be tested and at every Entity  that uses it in its test-class

    public function testNothing(): void
    {
        $this->assertTrue(true);
        // dumb, but to keep remember to test it at every Entity
    }
}
