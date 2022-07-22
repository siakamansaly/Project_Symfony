<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\SkillGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $posts = [];
        $hardSkills = $this->entityManager->getRepository(SkillGroup::class)->findBy(['organization' => 'HardSkills']);
        $softSkills = $this->entityManager->getRepository(SkillGroup::class)->findBy(['organization' => 'SoftSkills']);
        $otherSkills = $this->entityManager->getRepository(SkillGroup::class)->findBy(['organization' => 'OtherSkills']);
        $environments = $this->entityManager->getRepository(SkillGroup::class)->findBy(['organization' => 'Environment']);
        $experiences = $this->entityManager->getRepository(Experience::class)->findAll(['dateStart' => 'DESC']);
        return $this->render('frontend/homepage.html.twig', ['posts' => $posts, 'experiences' => $experiences, 'hardSkills' => $hardSkills, 'softSkills' => $softSkills, 'otherSkills' => $otherSkills, 'environments' => $environments]);
    }
}
