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
        $articles->setTitle('Mon Première article');
        $articles->setContent('Ici le contenu du 1er article');
        $articles->setAuthor('Kevin');

        $entityManager = $this->getDoctrine()->getManager();

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
        $articles->setTitle('PHP');
      

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($articles);
        $entityManager->flush();

        return new Response('<body>
            Category crée
        </body>');
    }



/**
 * @Route("/update")
 * @param ArticlesRepository $articlesRepository
 * @param ManagerRegistry $doctrine
 *
 */
    public function updateArticle(ArticlesRepository $articlesRepository,ManagerRegistry $doctrine,CategoryRepository $categoryRepository)
    {
        $entityManager = $doctrine->getManager();
        $article= $articlesRepository->findBytitle('Mon Première article');
        $category = $categoryRepository->findOneById(1);
        
 
        foreach ($article as $value) {
          $value->setCategory($category);
        }

        $entityManager->flush();
        dump($article);
            return new Response('<body>
                update
            </body>');
    }
}
