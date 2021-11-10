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
    /**
     * @Route("/article/{id}")
     */
    public function viewAction($id) {
        $article = $this->getDoctrine()->getRepository(Articles::class);
        $article = $article->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'Aucun article pour l\'id: ' . $id
            );
        }

        return $this->render(
            'articles/view.html.twig',
            array('article' => $article)
        );

    }
    /**
     * @Route("/articles/all")
     */
    public function showAction() {

        $articles = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $articles->findAll();

        return $this->render(
            'articles/list.html.twig',
            array('articles' => $articles)
        );
    }
    /**
     * @Route("/delete/{id}" , name="article_delete")
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository(Articles::class);
        $article = $article->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        $em->remove($article);
        $em->flush();

        return $this->redirect($this->generateUrl('articles_all'));

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
