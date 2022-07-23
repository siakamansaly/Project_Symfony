<?php

namespace App\Controller;

use App\Entity\SkillGroup;
use App\Form\SkillGroupType;
use App\Repository\SkillGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/skillgroup")
 */
class SkillGroupController extends AbstractController
{
    /**
     * @Route("/", name="app_skill_group_index", methods={"GET"})
     */
    public function index(SkillGroupRepository $skillGroupRepository): Response
    {
        return $this->render('skill_group/index.html.twig', [
            'skill_groups' => $skillGroupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_skill_group_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SkillGroupRepository $skillGroupRepository): Response
    {
        $skillGroup = new SkillGroup();
        $form = $this->createForm(SkillGroupType::class, $skillGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skillGroupRepository->add($skillGroup, true);

            return $this->redirectToRoute('app_skill_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('skill_group/new.html.twig', [
            'skill_group' => $skillGroup,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_skill_group_show", methods={"GET"})
     */
    public function show(SkillGroup $skillGroup): Response
    {
        return $this->render('skill_group/show.html.twig', [
            'skill_group' => $skillGroup,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_skill_group_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SkillGroup $skillGroup, SkillGroupRepository $skillGroupRepository): Response
    {
        $form = $this->createForm(SkillGroupType::class, $skillGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skillGroupRepository->add($skillGroup, true);

            return $this->redirectToRoute('app_skill_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('skill_group/edit.html.twig', [
            'skill_group' => $skillGroup,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_skill_group_delete", methods={"POST"})
     */
    public function delete(Request $request, SkillGroup $skillGroup, SkillGroupRepository $skillGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skillGroup->getId(), $request->request->get('_token'))) {
            $skillGroupRepository->remove($skillGroup, true);
        }

        return $this->redirectToRoute('app_skill_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
