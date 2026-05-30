<?php
namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $accounts = [
            ['Alice Johnson', '5000.00', 'EUR'],
            ['Bob Smith',     '3200.50', 'EUR'],
            ['Carol White',   '8750.00', 'USD'],
        ];

        foreach ($accounts as [$name, $balance, $currency]) {
            $account = new Account();
            $account->setName($name)->setBalance($balance)->setCurrency($currency);
            $manager->persist($account);
        }

        $manager->flush();
    }
}

?>