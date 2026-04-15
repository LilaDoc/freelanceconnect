<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<User> */
    private EntityRepository $userRepository;
    private string $path = '/user/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->manager->getRepository(User::class);

        foreach ($this->userRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'user[email]' => 'Testing',
            'user[roles]' => 'Testing',
            'user[password]' => 'Testing',
            'user[firstName]' => 'Testing',
            'user[lastName]' => 'Testing',
            'user[company]' => 'Testing',
            'user[SIRET]' => 'Testing',
            'user[subscriptionDate]' => 'Testing',
            'user[street]' => 'Testing',
            'user[city]' => 'Testing',
            'user[postalCode]' => 'Testing',
            'user[country]' => 'Testing',
        ]);

        self::assertResponseRedirects('/user');

        self::assertSame(1, $this->userRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new User();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setCompany('My Title');
        $fixture->setSIRET('My Title');
        $fixture->setSubscriptionDate('My Title');
        $fixture->setStreet('My Title');
        $fixture->setCity('My Title');
        $fixture->setPostalCode('My Title');
        $fixture->setCountry('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new User();
        $fixture->setEmail('Value');
        $fixture->setRoles('Value');
        $fixture->setPassword('Value');
        $fixture->setFirstName('Value');
        $fixture->setLastName('Value');
        $fixture->setCompany('Value');
        $fixture->setSIRET('Value');
        $fixture->setSubscriptionDate('Value');
        $fixture->setStreet('Value');
        $fixture->setCity('Value');
        $fixture->setPostalCode('Value');
        $fixture->setCountry('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[email]' => 'Something New',
            'user[roles]' => 'Something New',
            'user[password]' => 'Something New',
            'user[firstName]' => 'Something New',
            'user[lastName]' => 'Something New',
            'user[company]' => 'Something New',
            'user[SIRET]' => 'Something New',
            'user[subscriptionDate]' => 'Something New',
            'user[street]' => 'Something New',
            'user[city]' => 'Something New',
            'user[postalCode]' => 'Something New',
            'user[country]' => 'Something New',
        ]);

        self::assertResponseRedirects('/user');

        $fixture = $this->userRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getFirstName());
        self::assertSame('Something New', $fixture[0]->getLastName());
        self::assertSame('Something New', $fixture[0]->getCompany());
        self::assertSame('Something New', $fixture[0]->getSIRET());
        self::assertSame('Something New', $fixture[0]->getSubscriptionDate());
        self::assertSame('Something New', $fixture[0]->getStreet());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getPostalCode());
        self::assertSame('Something New', $fixture[0]->getCountry());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new User();
        $fixture->setEmail('Value');
        $fixture->setRoles('Value');
        $fixture->setPassword('Value');
        $fixture->setFirstName('Value');
        $fixture->setLastName('Value');
        $fixture->setCompany('Value');
        $fixture->setSIRET('Value');
        $fixture->setSubscriptionDate('Value');
        $fixture->setStreet('Value');
        $fixture->setCity('Value');
        $fixture->setPostalCode('Value');
        $fixture->setCountry('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/user');
        self::assertSame(0, $this->userRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
