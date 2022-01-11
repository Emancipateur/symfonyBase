<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\CreateArticleType;
use App\Repository\ArticlesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/abc")
     */
    public function CreateNewArticle(Request $request, ManagerRegistry $managerRegistry): Response
    {

    $article = new Articles();
    $form = $this->createForm(CreateArticleType::class, $article);
    $form->handleRequest($request);

            

         if($form->isSubmitted()&&$form->isValid()){
        $entityManager = $managerRegistry->getManager();

        $entityManager->persist($article);
        $entityManager->flush();
            $this->addFlash('Article','Article Envoyé');
            return $this->redirectToRoute('app_article_createnewarticle');

        }

        return $this->render('article/createNewArticle.html.twig', [
            'article' => $form->createView()
        ]);
    }
}
