<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        
        for ($u = 0; $u < 10; $u++) {
            $chrono = 1;

            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");

            $firstNameUser = $faker->firstName();
            $lastNameUser = $faker->lastName;
            $emailUserConcat = $firstNameUser . '.' . $lastNameUser . '@hotmail.fr';
            // Controle de l'email
            $emailUser = str_replace([" ", ".."], ["-", '.'], $emailUserConcat);
            $user->setFirstName($firstNameUser)
                ->setLastName($lastNameUser)
                ->setEmail($emailUser)
                ->setPassword($hash);

            $manager->persist($user);

            for ($c = 0; $c < mt_rand(5, 20); $c++) {
                $customer = new Customer();
                $firstNameCustomer = $faker->firstName();
                $lastNameCustomer = $faker->lastName;
                $company = $faker->company;
                $emailCustomerConcat = $firstNameCustomer . '.' . $lastNameCustomer . '@' . $company . '.fr';
                // Controle de l'email
                $email = str_replace([" ", ".."], ["-", '.'], $emailCustomerConcat);

                $customer->setFirstName($firstNameCustomer)
                    ->setLastName($lastNameCustomer)
                    ->setCompany($company)
                    ->setEmail($email)
                    ->setUser($user);

                $manager->persist($customer);

                for ($i = 0; $i < mt_rand(3, 10); $i++) {
                    $invoice = new Invoice();
                    $invoice->setAmount($faker->randomFloat(2, 250, 5000))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELLED']))
                        ->setCustomer($customer)
                        ->setChrono($chrono);

                    $chrono++;

                    $manager->persist($invoice);
                }
            }
        }
        $manager->flush();
    }
}
