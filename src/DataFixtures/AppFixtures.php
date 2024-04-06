<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Notes;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Récupérez l'utilisateur existant par son email ou un autre identifiant unique
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'example@gmail.com']);

        if (!$user) {
            throw new \LogicException('User not found');
        }

        for ($i = 0; $i < 10; $i++) {
            $note = new Notes();
            $note->setTitle('Note ' . $i);
            $note->setContent('Contenu de la note ' . $i);
            $note->setUser($user); // Assignez l'utilisateur existant à la note
            $manager->persist($note);
        }

        $manager->flush();
    }
}
