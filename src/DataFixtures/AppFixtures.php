<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CandidacyStatus;
use App\Entity\OffreMissionStatus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCategories($manager);
        $this->loadOffreMissionStatuses($manager);
        $this->loadCandidacyStatuses($manager);
        $this->loadUsers($manager);

        $manager->flush();
    }

    private function loadCategories(ObjectManager $manager): void
    {
        $categories = [
            ['code' => 'WEB_DEV', 'label' => 'Développement Web'],
            ['code' => 'MOBILE_DEV', 'label' => 'Développement Mobile'],
            ['code' => 'DESIGN', 'label' => 'Design & UX/UI'],
            ['code' => 'DATA', 'label' => 'Data & Intelligence Artificielle'],
            ['code' => 'DEVOPS', 'label' => 'DevOps & Infrastructure'],
        ];

        foreach ($categories as $data) {
            $category = new Category();
            $category->setCode($data['code']);
            $category->setLabel($data['label']);
            $manager->persist($category);
            
            $this->addReference('category_' . $data['code'], $category);
        }
    }

    private function loadOffreMissionStatuses(ObjectManager $manager): void
    {
        $statuses = [
            ['code' => 'PENDING', 'label' => 'En attente de validation'],
            ['code' => 'PUBLISHED', 'label' => 'Publiée'],
            ['code' => 'HIDDEN', 'label' => 'Masquée'],
        ];

        foreach ($statuses as $data) {
            $status = new OffreMissionStatus();
            $status->setCode($data['code']);
            $status->setLabel($data['label']);
            $manager->persist($status);
            
            $this->addReference('offre_status_' . $data['code'], $status);
        }
    }

    private function loadCandidacyStatuses(ObjectManager $manager): void
    {
        $statuses = [
            ['code' => 'PENDING', 'label' => 'En attente'],
            ['code' => 'ACCEPTED', 'label' => 'Acceptée'],
            ['code' => 'REFUSED', 'label' => 'Refusée'],
        ];

        foreach ($statuses as $data) {
            $status = new CandidacyStatus();
            $status->setCode($data['code']);
            $status->setLabel($data['label']);
            $manager->persist($status);
            
            $this->addReference('candidacy_status_' . $data['code'], $status);
        }
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'admin@freelanceconnect.fr',
                'roles' => ['ROLE_ADMIN'],
                'firstName' => 'Admin',
                'lastName' => 'System',
                'company' => 'FreelanceConnect',
            ],
            [
                'email' => 'client@freelanceconnect.fr',
                'roles' => ['ROLE_CLIENT'],
                'firstName' => 'Jean',
                'lastName' => 'Dupont',
                'company' => 'Dupont & Associés',
                'siret' => '12345678901234',
            ],
            [
                'email' => 'freelance@freelanceconnect.fr',
                'roles' => ['ROLE_FREELANCER'],
                'firstName' => 'Marie',
                'lastName' => 'Martin',
                'siret' => '98765432109876',
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setSubscriptionDate(new \DateTime());
            
            if (isset($data['company'])) {
                $user->setCompany($data['company']);
            }
            if (isset($data['siret'])) {
                $user->setSIRET($data['siret']);
            }
            
            // Mot de passe : "password" pour tous les comptes de test
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            
            $manager->persist($user);
            
            $this->addReference('user_' . strtolower($data['firstName']), $user);
        }
    }
}
