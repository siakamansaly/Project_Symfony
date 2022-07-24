<?php

namespace App\Test\Functional\Controller;

use App\Entity\Portfolio;
use App\Repository\PortfolioRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PortfolioControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PortfolioRepository $repository;
    private string $path = '/portfolio/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Portfolio::class);
        file_put_contents(__DIR__ . '/../../../public/assets/covers/tests/cover.txt', 'Ecriture dans un fichier');

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Portfolio');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $upload = new UploadedFile(
            __DIR__ . '/../../../public/assets/covers/tests/cover.txt',
            'cover.txt',
            'text/plain',
            UPLOAD_ERR_OK,
            true
        );

        $this->client->submitForm('Save', [
            'portfolio[title]' => 'Testing',
            'portfolio[createdAt]' => '2020-01-01',
            'portfolio[cover]' => $upload->getPathname(),
            'portfolio[content]' => 'Testing',
            'portfolio[link]' => 'Testing',
        ]);

        self::assertResponseRedirects('/portfolio/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $cover = $this->repository->findOneBy(['title' => 'Testing']);

        $fileRemove = new FileUploader(__DIR__.'/../../../public/assets/covers/');
        $fileRemove->remove($cover->getCover());
    }

    public function testShow(): void
    {
        $fixture = new Portfolio();
        $fixture->setTitle('My Title');
        $fixture->setCover('My Title');
        $fixture->setContent('My Title');
        $fixture->setLink('My Title');
        $fixture->setCreatedAt(new \DateTime());

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Portfolio');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Portfolio();
        $fixture->setTitle('My Title');
        $fixture->setCover('My Title');
        $fixture->setContent('My Title');
        $fixture->setLink('My Title');
        $fixture->setCreatedAt(new \DateTime());

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $upload = new UploadedFile(
            __DIR__ . '/../../../public/assets/covers/tests/cover.txt',
            'cover.txt',
            'text/plain',
            UPLOAD_ERR_OK,
            false
        );

        $this->client->submitForm('Update', [
            'portfolio[title]' => 'Something New',
            'portfolio[createdAt]' => '2020-01-01',
            'portfolio[cover]' => $upload->getPathname(),
            'portfolio[content]' => 'Something New',
            'portfolio[link]' => 'http://Something',
        ]);

        self::assertResponseRedirects('/portfolio/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('http://Something', $fixture[0]->getLink());
        self::assertSame('2020-01-01', $fixture[0]->getCreatedAt()->format('Y-m-d'));

        self::assertFileExists(__DIR__.'/../../../public/assets/covers/'.$fixture[0]->getCover());
        // remove the file
        $fileRemove = new FileUploader(__DIR__.'/../../../public/assets/covers/');
        $fileRemove->remove($fixture[0]->getCover());
    }

    public function testEditWithoutCover(): void
    {
        $fixture = new Portfolio();
        $fixture->setTitle('My Title');
        $fixture->setCover('My Title');
        $fixture->setContent('My Title');
        $fixture->setLink('My Title');
        $fixture->setCreatedAt(new \DateTime());

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'portfolio[title]' => 'Something New',
            'portfolio[createdAt]' => '2020-01-01',
            'portfolio[content]' => 'Something New',
            'portfolio[link]' => 'http://Something',
        ]);

        self::assertResponseRedirects('/portfolio/');

    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Portfolio();
        $fixture->setTitle('My Title');
        $fixture->setCover('My Title');
        $fixture->setContent('My Title');
        $fixture->setLink('My Title');
        $fixture->setCreatedAt(new \DateTime());

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/portfolio/');
    }
}
