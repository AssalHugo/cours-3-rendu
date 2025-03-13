<?php

namespace Tests;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $product = new Product('Laptop', ['USD' => 1000, 'EUR' => 900], 'tech');
        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame('Laptop', $product->getName());
        $this->assertSame(['USD' => 1000, 'EUR' => 900], $product->getPrices());
        $this->assertSame('tech', $product->getType());
    }

    public function testSetInvalidType(): void
    {
        $this->expectException(\Exception::class);
        new Product('Laptop', ['USD' => 1000], 'invalid');
    }

    public function testGetTVA(): void
    {
        $product = new Product('Apple', ['USD' => 1], 'food');
        $this->assertSame(0.1, $product->getTVA());

        $product = new Product('Laptop', ['USD' => 1000], 'tech');
        $this->assertSame(0.2, $product->getTVA());
    }

    public function testGetPrice(): void
    {
        $product = new Product('Laptop', ['USD' => 1000, 'EUR' => 900], 'tech');
        $this->assertSame(1000.0, $product->getPrice('USD'));
        $this->assertSame(900.0, $product->getPrice('EUR'));
    }

    public function testGetPriceInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $product = new Product('Laptop', ['USD' => 1000], 'tech');
        $product->getPrice('JPY');
    }
}