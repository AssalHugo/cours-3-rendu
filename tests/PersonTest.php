<?php

namespace Tests;

use App\Entity\Person;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testCreatePerson(): void
    {
        $person = new Person('John Doe', 'USD');
        $this->assertInstanceOf(Person::class, $person);
        $this->assertSame('John Doe', $person->getName());
        $this->assertInstanceOf(\App\Entity\Wallet::class, $person->getWallet());
        $this->assertSame(0.0, $person->getWallet()->getBalance());
        $this->assertSame('USD', $person->getWallet()->getCurrency());
    }

    public function testHasFund(): void
    {
        $person = new Person('John Doe', 'USD');
        $this->assertFalse($person->hasFund());
        $person->getWallet()->addFund(100);
        $this->assertTrue($person->hasFund());
    }

    public function testTransfertFund(): void
    {
        $person1 = new Person('John Doe', 'USD');
        $person2 = new Person('Jane Doe', 'USD');
        $person1->getWallet()->addFund(100);
        $person1->transfertFund(50, $person2);
        $this->assertSame(50.0, $person1->getWallet()->getBalance());
        $this->assertSame(50.0, $person2->getWallet()->getBalance());
    }

    public function testTransfertFundDifferentCurrencies(): void
    {
        $this->expectException(\Exception::class);
        $person1 = new Person('John Doe', 'USD');
        $person2 = new Person('Jane Doe', 'EUR');
        $person1->transfertFund(50, $person2);
    }

    public function testDivideWallet(): void
    {
        $person1 = new Person('John Doe', 'USD');
        $person2 = new Person('Jane Doe', 'USD');
        $person3 = new Person('Jim Doe', 'USD');
        $person1->getWallet()->addFund(300);
        $person1->divideWallet([$person2, $person3]);
        $this->assertSame(0.0, $person1->getWallet()->getBalance());
        $this->assertSame(150.0, $person2->getWallet()->getBalance());
        $this->assertSame(150.0, $person3->getWallet()->getBalance());
    }

    public function testDivideWalletDifferentCurrencies(): void
    {
        $person1 = new Person('John Doe', 'USD');
        $person2 = new Person('Jane Doe', 'EUR');
        $person3 = new Person('Jim Doe', 'USD');
        $person1->getWallet()->addFund(200);
        $person1->divideWallet([$person2, $person3]);
        $this->assertSame(0.0, $person1->getWallet()->getBalance());
        $this->assertSame(0.0, $person2->getWallet()->getBalance());
        $this->assertSame(200.0, $person3->getWallet()->getBalance());
    }

    public function testBuyProduct(): void
    {
        $person = new Person('John Doe', 'USD');
        $product = new Product('Laptop', ['USD' => 1000], 'tech');
        $person->getWallet()->addFund(1000);
        $person->buyProduct($product);
        $this->assertSame(0.0, $person->getWallet()->getBalance());
    }

    public function testBuyProductDifferentCurrency(): void
    {
        $this->expectException(\Exception::class);
        $person = new Person('John Doe', 'USD');
        $product = new Product('Laptop', ['EUR' => 1000], 'tech');
        $person->buyProduct($product);
    }
}