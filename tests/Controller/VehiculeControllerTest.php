<?php

namespace App\Tests\Controller;

use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VehiculeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $vehiculeRepository;
    private string $path = '/vehicule/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->vehiculeRepository = $this->manager->getRepository(Vehicule::class);

        foreach ($this->vehiculeRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicule index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'vehicule[immatriculation]' => 'Testing',
            'vehicule[couleur]' => 'Testing',
            'vehicule[slug]' => 'Testing',
            'vehicule[marque]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->vehiculeRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setImmatriculation('My Title');
        $fixture->setCouleur('My Title');
        $fixture->setSlug('My Title');
        $fixture->setMarque('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicule');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setImmatriculation('Value');
        $fixture->setCouleur('Value');
        $fixture->setSlug('Value');
        $fixture->setMarque('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'vehicule[immatriculation]' => 'Something New',
            'vehicule[couleur]' => 'Something New',
            'vehicule[slug]' => 'Something New',
            'vehicule[marque]' => 'Something New',
        ]);

        self::assertResponseRedirects('/vehicule/');

        $fixture = $this->vehiculeRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getImmatriculation());
        self::assertSame('Something New', $fixture[0]->getCouleur());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getMarque());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setImmatriculation('Value');
        $fixture->setCouleur('Value');
        $fixture->setSlug('Value');
        $fixture->setMarque('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/vehicule/');
        self::assertSame(0, $this->vehiculeRepository->count([]));
    }
}
