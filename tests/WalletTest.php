<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testCreateWallet(): void
    {
        $wallet = new Wallet('USD');
        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertSame(0.0, $wallet->getBalance());
        $this->assertSame('USD', $wallet->getCurrency());
    }

    public function testSetBalance(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->setBalance(-1);
    }

    public function testSetCurrency(): void
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->setCurrency('JPY');
    }

    public function testRemoveFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(100);
        $wallet->removeFund(50);
        $this->assertSame(50.0, $wallet->getBalance());

        $this->expectException(\Exception::class);
        $wallet->removeFund(-1);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');
        $wallet->removeFund(51);
    }

    public function testAddFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $this->assertSame(100.0, $wallet->getBalance());

        $this->expectException(\Exception::class);
        $wallet->addFund(-1);
    }
}