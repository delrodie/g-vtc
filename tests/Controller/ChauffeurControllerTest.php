<?php

namespace App\Tests\Controller;

use App\Entity\Chauffeur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ChauffeurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $chauffeurRepository;
    private string $path = '/chauffeur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->chauffeurRepository = $this->manager->getRepository(Chauffeur::class);

        foreach ($this->chauffeurRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Chauffeur index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'chauffeur[matricule]' => 'Testing',
            'chauffeur[nom]' => 'Testing',
            'chauffeur[telephone]' => 'Testing',
            'chauffeur[permis]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->chauffeurRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chauffeur();
        $fixture->setMatricule('My Title');
        $fixture->setNom('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setPermis('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Chauffeur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chauffeur();
        $fixture->setMatricule('Value');
        $fixture->setNom('Value');
        $fixture->setTelephone('Value');
        $fixture->setPermis('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'chauffeur[matricule]' => 'Something New',
            'chauffeur[nom]' => 'Something New',
            'chauffeur[telephone]' => 'Something New',
            'chauffeur[permis]' => 'Something New',
        ]);

        self::assertResponseRedirects('/chauffeur/');

        $fixture = $this->chauffeurRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getMatricule());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getPermis());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chauffeur();
        $fixture->setMatricule('Value');
        $fixture->setNom('Value');
        $fixture->setTelephone('Value');
        $fixture->setPermis('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/chauffeur/');
        self::assertSame(0, $this->chauffeurRepository->count([]));
    }
}
