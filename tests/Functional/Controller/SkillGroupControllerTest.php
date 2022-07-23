<?php

namespace App\Tests\Functional\Controller;

use App\Entity\SkillGroup;
use App\Repository\SkillGroupRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SkillGroupControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SkillGroupRepository $repository;
    private string $path = '/skillgroup/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(SkillGroup::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Skill Group');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'skill_group[title]' => 'Testing',
            'skill_group[emoji]' => 'Testing',
            'skill_group[organization]' => 'HardSkills',
        ]);

        self::assertResponseRedirects('/skillgroup/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new SkillGroup();
        $fixture->setTitle('My Title');
        $fixture->setEmoji('My Title');
        $fixture->setOrganization('HardSkills');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Skill Group');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new SkillGroup();
        $fixture->setTitle('My Title');
        $fixture->setEmoji('My Title');
        $fixture->setOrganization('HardSkills');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'skill_group[title]' => 'Something New',
            'skill_group[emoji]' => 'Something New',
            'skill_group[organization]' => 'HardSkills',
        ]);

        self::assertResponseRedirects('/skillgroup/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getEmoji());
        self::assertSame('HardSkills', $fixture[0]->getOrganization());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new SkillGroup();
        $fixture->setTitle('My Title');
        $fixture->setEmoji('My Title');
        $fixture->setOrganization('HardSkills');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/skillgroup/');
    }
}
