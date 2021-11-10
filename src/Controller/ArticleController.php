<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article as Articles;
class ArticleController extends AbstractController
{
        /**
     * @Route("/article/new")
     */
    public function createAction(Request $request) {
        $articles = new Articles();
        $form = $this->createFormBuilder($articles)
            ->add('titre', TextType::class)
            ->add('auteur', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $articles = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($articles);
            $em->flush();
            echo 'Envoyé';
        }
        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    // /**
    //  * @Route("/article", name="article")
    //  */
    // public function index(): Response
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/ArticleController.php',
    //     ]);
    // }
}
