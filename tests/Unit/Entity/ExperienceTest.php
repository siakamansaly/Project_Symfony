<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Experience;
use App\Entity\ExperienceDetails;
use PHPUnit\Framework\TestCase;

class ExperienceTest extends TestCase
{
    public function testIsTrue(): void
    {
        $experience = new Experience();
        $date = new \DateTime();
        $detail = new ExperienceDetails();

        $experience->setCity('Paris');
        $experience->setContext('Context');
        $experience->setDateStart($date);
        $experience->setDateEnd($date);
        $experience->setFormation(1);
        $experience->setTitle('Title');
        $experience->setSociety('Society');
        $experience->addExperienceDetail($detail);

        $this->assertEquals('Paris', $experience->getCity());
        $this->assertEquals('Context', $experience->getContext());
        $this->assertEquals($date, $experience->getDateStart());
        $this->assertEquals($date, $experience->getDateEnd());
        $this->assertEquals(1, $experience->getFormation());
        $this->assertEquals('Title', $experience->getTitle());
        $this->assertEquals('Society', $experience->getSociety());
        $this->assertEquals($detail->getId(), $experience->getExperienceDetails()->get($detail->getId()));

    }

    public function testIsFalse(): void
    {
        $experience = new Experience();
        $date = new \DateTime();
        $dateFalse = new \DateTime('2020-01-01');
        $detail = new ExperienceDetails();
        $detailFalse = new ExperienceDetails();

        $experience->setCity('Paris');
        $experience->setContext('Context');
        $experience->setDateStart($date);
        $experience->setDateEnd($date);
        $experience->setFormation(1);
        $experience->setTitle('Title');
        $experience->setSociety('Society');
        $experience->addExperienceDetail($detail);

        $this->assertFalse('ParisFalse' === $experience->getCity());
        $this->assertFalse('ContextFalse' === $experience->getContext());
        $this->assertFalse($dateFalse === $experience->getDateStart());
        $this->assertFalse($dateFalse === $experience->getDateEnd());
        $this->assertFalse(0 === $experience->getFormation());
        $this->assertFalse('TitleFalse' === $experience->getTitle());
        $this->assertFalse('SocietyFalse' === $experience->getSociety());
        $this->assertFalse($detailFalse === $experience->getExperienceDetails());

    }

    public function testIsEmpty(): void
    {
        $experience = new Experience();

        $this->assertEmpty($experience->getCity());
        $this->assertEmpty($experience->getContext());
        $this->assertEmpty($experience->getDateStart());
        $this->assertEmpty($experience->getDateEnd());
        $this->assertEmpty($experience->getFormation());
        $this->assertEmpty($experience->getTitle());
        $this->assertEmpty($experience->getSociety());
        $this->assertEmpty($experience->getExperienceDetails());
    }
}
