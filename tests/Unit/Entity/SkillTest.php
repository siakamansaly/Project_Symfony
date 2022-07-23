<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Skill;
use App\Entity\SkillGroup;
use PHPUnit\Framework\TestCase;

class SkillTest extends TestCase
{
    public function testIsTrue(): void
    {
        $skill = new Skill();
        $group = new SkillGroup();
        $group->setTitle('Group');

        $skill->setTitle('Name');
        $skill->setStars(5);
        $skill->setCategory($group);

        $this->assertEquals('Name', $skill->getTitle());
        $this->assertEquals(5, $skill->getStars());
        $this->assertEquals($group->getTitle(), $skill->getCategory()->getTitle());

    }

    public function testIsFalse(): void
    {
        $skill = new Skill();
        $group = new SkillGroup();
        $group->setTitle('Group');

        $skill->setTitle('Name');
        $skill->setStars(5);
        $skill->setCategory($group);

        $this->assertFalse('NameFalse' === $skill->getTitle());
        $this->assertFalse(0 === $skill->getStars());
        $this->assertFalse('GroupFalse' === $skill->getCategory()->getTitle());

    }

    public function testIsEmpty(): void
    {
        $skill = new Skill();

        $this->assertEmpty($skill->getTitle());
        $this->assertEmpty($skill->getStars());
        $this->assertEmpty($skill->getCategory());
        $this->assertEmpty($skill->getId());
    }
}
