<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $formation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $society;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceDetails::class, mappedBy="experience", orphanRemoval=true, cascade={"persist"})
     */
    private $experienceDetails;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $context;

    public function __construct()
    {
        $this->experienceDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getFormation(): ?int
    {
        return $this->formation;
    }

    public function setFormation(?int $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(string $society): self
    {
        $this->society = $society;

        return $this;
    }

    /**
     * @return Collection<int, ExperienceDetails>
     */
    public function getExperienceDetails(): Collection
    {
        return $this->experienceDetails;
    }

    public function addExperienceDetail(ExperienceDetails $experienceDetail): self
    {
        if (!$this->experienceDetails->contains($experienceDetail)) {
            $this->experienceDetails[] = $experienceDetail;
            $experienceDetail->setExperience($this);
        }

        return $this;
    }

    public function removeExperienceDetail(ExperienceDetails $experienceDetail): self
    {
        if ($this->experienceDetails->removeElement($experienceDetail)) {
            // set the owning side to null (unless already changed)
            if ($experienceDetail->getExperience() === $this) {
                $experienceDetail->setExperience(null);
            }
        }

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
