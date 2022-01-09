<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Category;
use App\Repository\ArticlesRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * 
     *@Route("/create")
     * @param ArticlesRepository $articlesRepository
     
     */
    public function createArticle( ManagerRegistry $managerRegistry)
    {
        $articles = new Articles;
        $articles->setTitle('Mon Deuxième article');
        $articles->setContent('Ici le contenu du 2ème article');
        $articles->setAuthor('Ilan');

        $entityManager = $managerRegistry->getManager();

        $entityManager->persist($articles);
        $entityManager->flush();

        return new Response('<body>
            Article crée
        </body>');
    }
    /**
     * 
     *@Route("/createCategory")
     */
    public function createCategory( ManagerRegistry $managerRegistry)
    {
        $articles = new Category;
        $articles->setTitle('JS');
      

        $entityManager = $managerRegistry->getManager();

        $entityManager->persist($articles);
        $entityManager->flush();

        return new Response('<body>
            Category crée
        </body>');
    }



/**
 * @Route("/update/{id}")
 * @param ArticlesRepository $articlesRepository
 * @param ManagerRegistry $doctrine
 *
 */
    public function updateArticle($id,ArticlesRepository $articlesRepository,ManagerRegistry $managerRegistry,CategoryRepository $categoryRepository)
    {
        $entityManager = $managerRegistry->getManager();
        $article= $articlesRepository->findById($id);
        $category = $categoryRepository->findOneById(2);
       
        foreach ($article as $value) {
          $value->setCategory($category);
        }

        $entityManager->flush();

            return new Response('<body>
                update
            </body>');
    }


    /**
     * @Route("/showarticles")
     * @param ArticlesRepository $articlesRepository
     * @return void
     */
    public function showArticles(ArticlesRepository $articlesRepository, ManagerRegistry $managerRegistry)
    {
        $articles = $articlesRepository->findall();

        foreach ($articles as $article){
        $article->getCategory()->getTitle();
    
        }


        return $this->render('default/showArticles.html.twig',["articles" => $articles]);
    }
}

