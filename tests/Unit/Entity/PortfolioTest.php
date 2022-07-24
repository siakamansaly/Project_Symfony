<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Portfolio;
use PHPUnit\Framework\TestCase;

class PortfolioTest extends TestCase
{
    public function testIsTrue(): void
    {
        $portfolio = new Portfolio();
        $date = new \DateTime();
        $portfolio->setTitle('Title');
        $portfolio->setCover('Cover.jpg');
        $portfolio->setContent('Lorem ipsum');
        $portfolio->setLink('https://www.google.com');
        $portfolio->setCreatedAt($date);

        $this->assertEquals('Title', $portfolio->getTitle());
        $this->assertEquals('Cover.jpg', $portfolio->getCover());
        $this->assertEquals('Lorem ipsum', $portfolio->getContent());
        $this->assertEquals('https://www.google.com', $portfolio->getLink());
        $this->assertEquals($date, $portfolio->getCreatedAt());

    }

    public function testIsFalse(): void
    {
        $portfolio = new Portfolio();
        $date = new \DateTime();
        $dateFalse = new \DateTime('2020-01-01');

        $portfolio->setTitle('Title');
        $portfolio->setCover('Cover.jpg');
        $portfolio->setContent('Lorem ipsum');
        $portfolio->setLink('https://www.google.com');
        $portfolio->setCreatedAt($date);

        $this->assertFalse('TitleFalse' === $portfolio->getTitle());
        $this->assertFalse('CoverFalse.jpg' === $portfolio->getCover());
        $this->assertFalse('LoremFalse' === $portfolio->getContent());
        $this->assertFalse('https://www.google.comFalse' === $portfolio->getLink());
        $this->assertFalse($dateFalse === $portfolio->getCreatedAt());

    }

    public function testIsEmpty(): void
    {
        $portfolio = new Portfolio();

        $this->assertEmpty($portfolio->getTitle());
        $this->assertEmpty($portfolio->getCover());
        $this->assertEmpty($portfolio->getContent());
        $this->assertEmpty($portfolio->getLink());
        $this->assertEmpty($portfolio->getCreatedAt());
        $this->assertEmpty($portfolio->getId());
        
    }
}
