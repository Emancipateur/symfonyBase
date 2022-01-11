<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactformType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController

{


 
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request,  ManagerRegistry $managerRegistry): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactformType::class,$contact );
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
        $entityManager = $managerRegistry->getManager();

        $entityManager->persist($contact);
        $entityManager->flush();
            $this->addFlash('Message','Message Envoyé');
            return $this->redirectToRoute('contact');

        }

        return $this->render('contact/index.html.twig', [
            'contact' => $form->createView() ,
        ]);
    }


}
