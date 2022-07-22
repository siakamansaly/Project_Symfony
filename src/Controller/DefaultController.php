<?php

namespace App\Controller;

use App\Entity\Experience;
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
        $experiences = $this->entityManager->getRepository(Experience::class)->findAll(['dateStart' => 'DESC']);
        return $this->render('frontend/homepage.html.twig', ['posts' => $posts, 'experiences' => $experiences]);
    }
}
