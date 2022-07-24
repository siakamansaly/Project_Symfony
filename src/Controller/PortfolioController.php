<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use App\Service\FileUploader;
use App\Repository\PortfolioRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/portfolio")
 */
class PortfolioController extends AbstractController
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }
    /**
     * @Route("/", name="app_portfolio_index", methods={"GET"})
     */
    public function index(PortfolioRepository $portfolioRepository): Response
    {
        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_portfolio_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PortfolioRepository $portfolioRepository): Response
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cover = $form->get('cover')->getData();
            if ($cover) {
                $coverFileName = $this->fileUploader->upload($cover, 'cover');
                $portfolio->setCover($coverFileName);
            }
        
            $portfolioRepository->add($portfolio, true);

            return $this->redirectToRoute('app_portfolio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('portfolio/new.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_portfolio_show", methods={"GET"})
     */
    public function show(Portfolio $portfolio): Response
    {
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_portfolio_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Portfolio $portfolio, PortfolioRepository $portfolioRepository): Response
    {
        $removePicture = $portfolio->getCover();
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover = $form->get('cover')->getData();
            
            switch ($cover) {
                case null:
                    $portfolio->setCover($removePicture);
                    break;
                default:
                    $coverFileName = $this->fileUploader->upload($cover, 'cover');
                    $portfolio->setCover($coverFileName);

                    if ($removePicture) {
                        $this->fileUploader->remove($removePicture);
                    }
                    break;
            }
            $portfolioRepository->add($portfolio, true);

            return $this->redirectToRoute('app_portfolio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('portfolio/edit.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_portfolio_delete", methods={"POST"})
     */
    public function delete(Request $request, Portfolio $portfolio, PortfolioRepository $portfolioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $removePicture = $portfolio->getCover();
            if ($removePicture) {
                $this->fileUploader->remove($removePicture);
            }
            $portfolioRepository->remove($portfolio, true);
        }

        return $this->redirectToRoute('app_portfolio_index', [], Response::HTTP_SEE_OTHER);
    }
}
