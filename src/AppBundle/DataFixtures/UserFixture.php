<?php
/**
 * Created by PhpStorm.
 * User: Dmitri_Sobolevski
 * Date: 28.01.19
 * Time: 14:39
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Users;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Tests\Common\DataFixtures\BaseFixture1;

class UserFixture extends BaseFixture1{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) {
            $user = new Users();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setUsername($this->faker->setUsername);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));
            return $user;
        });
        $manager->flush();
    }
}