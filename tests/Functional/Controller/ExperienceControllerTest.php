<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Experience;
use App\Entity\ExperienceDetails;
use App\Repository\ExperienceRepository;
use App\Repository\ExperienceDetailsRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExperienceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ExperienceRepository $repository;
    private ExperienceDetailsRepository $repositoryDetails;
    private string $path = '/experience/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Experience::class);
        $this->repositoryDetails = (static::getContainer()->get('doctrine'))->getRepository(ExperienceDetails::class);
        foreach ($this->repositoryDetails->findAll() as $object) {
            $this->repositoryDetails->remove($object, true);
        }
        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Experience');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        //$this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'experience[dateStart]' => '2020-01-01',
            'experience[dateEnd]' => '2020-01-01',
            'experience[city]' => 'Testing',
            'experience[context]' => 'Testing',
            'experience[formation]' => 1,
            'experience[title]' => 'Testing',
            'experience[society]' => 'Testing',
        ]);

        self::assertResponseRedirects('/experience/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Experience();
        $fixture->setDateStart(new \DateTime('2020-01-01'));
        $fixture->setDateEnd(new \DateTime('2020-01-01'));
        $fixture->setCity('My Title');
        $fixture->setContext('My Context');
        $fixture->setFormation(1);
        $fixture->setTitle('My Title');
        $fixture->setSociety('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Experience');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Experience();
        $fixture->setDateStart(new \DateTime('2020-01-01'));
        $fixture->setDateEnd(new \DateTime('2020-01-01'));
        $fixture->setCity('My Title');
        $fixture->setContext('My Context');
        $fixture->setFormation(null);
        $fixture->setTitle('My Title');
        $fixture->setSociety('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'experience[dateStart]' => '2020-01-01',
            'experience[dateEnd]' => '2020-01-01',
            'experience[city]' => 'Something New',
            'experience[context]' => 'Something New',
            'experience[formation]' => 1,
            'experience[title]' => 'Something New',
            'experience[society]' => 'Something New',
        ]);

        self::assertResponseRedirects('/experience/');

        $fixture = $this->repository->findAll();

        self::assertSame('2020-01-01', $fixture[0]->getDateStart()->format('Y-m-d'));
        self::assertSame('2020-01-01', $fixture[0]->getDateEnd()->format('Y-m-d'));
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame(1, $fixture[0]->getFormation());
        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getSociety());
    }

    public function testRemove(): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Experience();
        $detail1 = new ExperienceDetails();
        $detail1->setContent('Content 1');
        $detail2 = new ExperienceDetails();
        $detail2->setContent('Content 2');
        $fixture->setDateStart(new \DateTime('2020-01-01'));
        $fixture->setDateEnd(new \DateTime('2020-01-01'));
        $fixture->setCity('My Title');
        $fixture->setFormation(1);
        $fixture->setTitle('My Title');
        $fixture->setSociety('My Title');
        $fixture->addExperienceDetail($detail1);
        $fixture->addExperienceDetail($detail2);

        $fixture->removeExperienceDetail($detail1);

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/experience/');
    }

    public function testAddRemoveDetails(): void
    {
        $experience = new Experience();
        $experience->setDateStart(new \DateTime('2020-01-01'));
        $experience->setDateEnd(new \DateTime('2020-01-01'));
        $experience->setCity('My Title');
        $experience->setContext('My Context');
        $experience->setFormation(1);
        $experience->setTitle('My Title');
        $experience->setSociety('My Title');

        $detail = new ExperienceDetails();
        $detail->setContent('Content 1');
        $detail->setExperience($experience);
        $this->repositoryDetails->add($detail, true);

        $this->assertSame(1, count($this->repositoryDetails->findAll()));

        $this->repositoryDetails->remove($detail, true);
        $this->assertSame(0, count($this->repositoryDetails->findAll()));
    }


}
