<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Experience;
use PHPUnit\Framework\TestCase;
use App\Entity\ExperienceDetails;

class ExperienceDetailsTest extends TestCase
{    
    public function testIsTrue(): void
    {
        $experience = new Experience();
        $detail = new ExperienceDetails();

        $detail->setContent('Content');
        $detail->setExperience($experience);
        $this->assertEquals('Content', $detail->getContent());
        $this->assertEquals($experience->getId(), $detail->getExperience()->getId());

    }

    public function testIsFalse(): void
    {
        $experience = new Experience();

        $detail = new ExperienceDetails();
        $detail->setContent('Content');
        $detail->setExperience($experience);
        $this->assertFalse('ContentFalse' === $detail->getContent());
    }

    public function testIsEmpty(): void
    {
        $detail = new ExperienceDetails();
        $this->assertEmpty($detail->getContent());
        $this->assertEmpty($detail->getExperience());
    }

}
