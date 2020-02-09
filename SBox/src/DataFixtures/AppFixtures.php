<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Groups;
use App\Entity\Message;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //YnovUsers
        for ($i = 1; $i <= 3; $i++) {
            $user = new User;
            $user->setUsername('user' . $i);
            $user->setEmail('user' . $i . '@gmail.com');
            $password = $this->encoder->encodePassword($user, 'Ynov2020');
            $user->setPassword($password);
            $user->setPhoto('imageProfil' . rand(1, 300000000) . '.jpg');
            $manager->persist($user);

            for ($j = 1; $j <= 3; $j++) {
                $group = new Groups;
                $group->setNom('Groupe N° ' . $j . ' du user ' . $i);
                $group->setPhoto('imageGroupe' . rand(1, 99999999) . '.jpg');
                $group->setDate(new \DateTime('now'));

                $group->addUser($user); //Les users dans le groupe 

                $group->setUserAdmin($user); //L'admin du groupe

                //$user->addGroups($groups); //liste des groupes attribués à l'utilisateur
                $manager->persist($group);

                for ($x = 1; $x <= rand(1, 3); $x++) {
                    $message = new Message;
                    $message->setContent('Message N° ' . $x);
                    $message->setDateTime(new \DateTime('now'));
                    $message->setState(1);
                    $message->setGroupe($group);
                    $message->setUser($user); //auteur du message
                    $group->addMessage($message); // Les messages du groupe
                    $manager->persist($message);
                }
            }
        }






        $manager->flush();
    }
}
