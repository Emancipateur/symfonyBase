<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Articles;
use App\Entity\Category;
use App\Repository\ArticlesRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
    public function showArticles(ArticlesRepository $articlesRepository)
    {
        $articles = $articlesRepository->findall();

        foreach ($articles as $article){
        $article->getCategory()->getTitle();
    
        }
        return $this->render('default/showArticles.html.twig',["articles" => $articles]);
    }


    /**
     * @Route("/showarticle/{id}")
     * @param ArticlesRepository $articlesRepository
     * @return void
     */
    public function showArticle($id, ArticlesRepository $articlesRepository)
    {
        $article = $articlesRepository->findById($id);

        return $this->render('default/showArticle.html.twig',["article" => $article[0]]);
    }

        /**
     * @Route("/showcategory")
     */
    public function showCategory(CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findall();
     
        
        return $this->render('default/showCategory.html.twig',["category" => $category]);
    }
    /** 
    * @Route("/showcategory/{id}")
    */
   public function showOneCategory($id, CategoryRepository $categoryRepository)
   {
       $category = $categoryRepository->find($id);
       $article =  $category->getArticles();
             
       return $this->render('default/showOneCategory.html.twig',["category" => $article[0]]);
   }




/**
 *@Route("/userForm")
 */
public function new(Request $request): Response
{
    $user = new User();

    $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        dd($user);
    }

 return $this->render('default/user.html.twig',["form" => $form->createView()]);


}
/**
 * @Route("/search")
 */
    public function searchByTerm(Request $request, ArticlesRepository $articlesRepository)

    {

        $term = $request->query->get('q');
       $articles = $articlesRepository->searchByTerm($term);
        
        return $this->render('default/search.html.twig',["articles" => $articles]);
    }
}


