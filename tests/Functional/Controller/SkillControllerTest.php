<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Skill;
use App\Entity\SkillGroup;
use App\Repository\SkillGroupRepository;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SkillControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SkillRepository $repository;
    private SkillGroupRepository $repositoryGroup;
    private string $path = '/skill/';
    private $group;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Skill::class);
        $this->repositoryGroup = (static::getContainer()->get('doctrine'))->getRepository(SkillGroup::class);
        $this->group = $this->repositoryGroup->findOneBy(['title' => 'Testing']);
        if (null === $this->group) {
            $this->group = new SkillGroup();
            $this->group->setTitle('Testing');
            $this->repositoryGroup->add($this->group, true);
        }
        

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Skill');

    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);


        $this->client->submitForm('Save', [
            'skill[title]' => 'Testing',
            'skill[stars]' => 5,
            'skill[category]' => $this->group->getId(),
        ]);

        self::assertResponseRedirects('/skill/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Skill();
        $fixture->setTitle('My Title');
        $fixture->setStars(5);
        $fixture->setCategory($this->group);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Skill');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        
        $fixture = new Skill();
        $fixture->setTitle('My Title');
        $fixture->setStars(5);
        $fixture->setCategory($this->group);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'skill[title]' => 'Something New',
            'skill[stars]' => 5,
            'skill[category]' => $this->group->getId(),
        ]);

        self::assertResponseRedirects('/skill/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame(5, $fixture[0]->getStars());
        self::assertSame($this->group->getId(), $fixture[0]->getCategory()->getId());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Skill();
        $fixture->setTitle('My Title');
        $fixture->setStars(5);
        $fixture->setCategory($this->group);

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/skill/');
    }
}
