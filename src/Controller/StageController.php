<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonneRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/stage")
 */
class StageController extends AbstractController
{
    /**
     * @Route("/", name="app_stage_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $stages = $entityManager
            ->getRepository(Stage::class)
            ->findAll();

        return $this->render('stage/index.html.twig', [
            'stages' => $stages,
        ]);
    }
     /**
     * @Route("/adminstage", name="app_stage_indexx", methods={"GET"})
     */
    public function indexx(EntityManagerInterface $entityManager): Response
    {
        
        $stages = $entityManager
        ->getRepository(Stage::class)
        ->findAll();

        return $this->render('stage/Adminstage.html.twig',
         [
             'stages' => $stages,
        ]);
    }

    /**
     * @Route("/new", name="app_stage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, PersonneRepository $repository): Response
    {
        $stage = new Stage();
        $personne = $repository->find(107);
        $stage->setIdpersonne($personne);
        $stage->setDatefin(new \DateTime("2023-10-10"));
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stage);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Stage Ajouter Avec Succèe'

            );

            return $this->redirectToRoute('app_stage_mesStage', ['idpersonne' => "107"], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stage/new.html.twig', [
            'stage' => $stage,
            'f1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idstage}/show", name="app_stage_show", methods={"GET","POST"})
     */
    public function show(Stage $stage): Response
    {
        
        return $this->render('stage/show.html.twig', [
            'stage' => $stage,
        ]);
    }
    

    /**
     * @Route("/{idstage}/edit", name="app_stage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Stage Modifier Avec Succèe'

            );

            return $this->redirectToRoute('app_stage_mesStage', ['idpersonne' => "107"], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stage/edit.html.twig', [
            'stage' => $stage,
            'f1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idstage}/delete", name="app_stage_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stage->getIdstage(), $request->request->get('_token'))) {
            $entityManager->remove($stage);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Stage supprimer Avec Succèe'

            );
        }

        return $this->redirectToRoute('app_stage_mesStage', ['idpersonne' => "107"], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/mesStage/{idpersonne}", name="app_stage_mesStage", methods={"GET", "POST"})
     */
   public function MesStages()
   {
       $stage = $this->getDoctrine()
       ->getRepository(Stage::class)
       ->ListStageByIdPersonne(107);
        return $this-> render('stage/mesStage.html.twig',
        ['stage' => $stage]);
   }
    

}