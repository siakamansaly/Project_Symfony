<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Skill;
use App\Entity\SkillGroup;
use PHPUnit\Framework\TestCase;

class SkillGroupTest extends TestCase
{
    public function testIsTrue(): void
    {
        $group = new SkillGroup();
        $skill = new Skill();
        $skill->setTitle('Name');
        $skill->setStars(5);
        $skill->setCategory($group);

        $group->setTitle('Group');
        $group->setEmoji('Description');
        $group->setOrganization('Testing');
        $group->addSkill($skill);


        $this->assertEquals('Group', $group->getTitle());
        $this->assertEquals('Description', $group->getEmoji());
        $this->assertEquals('Testing', $group->getOrganization());
        $this->assertEquals('Name', $group->getSkills()[0]->getTitle());

        $group->removeSkill($skill);
        $this->assertEmpty($group->getSkills());

    }

    public function testIsFalse(): void
    {
        $group = new SkillGroup();
        $group->setTitle('Group');
        $group->setEmoji('Description');
        $group->setOrganization('Testing');

        $this->assertFalse('GroupFalse' === $group->getTitle());
        $this->assertFalse('DescriptionFalse' === $group->getEmoji());
        $this->assertFalse('TestingFalse' === $group->getOrganization());

    }

    public function testIsEmpty(): void
    {
        $group = new SkillGroup();

        $this->assertEmpty($group->getTitle());
        $this->assertEmpty($group->getEmoji());
        $this->assertEmpty($group->getOrganization());
        $this->assertEmpty($group->getId());
    }
}
