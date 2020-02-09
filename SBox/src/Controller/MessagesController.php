<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\Message;
use App\Form\GroupeType;
use App\Form\MessageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(Request $request)
    {
        //cherche l'user de la session
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //Trouve les groupes de l'utilisateur
        $repository = $this->getDoctrine()->getRepository(Groups::class);
        $groups = $repository->findAll();

        // Form de la création du groupe
        $manager = $this -> getDoctrine() -> getManager();

        $groupForm = new Groups();

        $form = $this -> createForm(GroupeType::class, $groupForm);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()) {
            $manager -> persist($groupForm);
            $groupForm->setDate(new \DateTime('now'));
            $groupForm->SetUserAdmin($user);
            $groupForm->addUser($user);
            $manager -> flush();
            
            $this -> addFlash('alert', 'success Le User a bien été enregisté');
            return $this -> redirectToRoute('messages');
        }

        return $this->render('messages/index.html.twig', [
            'groups' => $groups, 'groupForm' => $form-> createView()
        ]);
    }

    /**
     * @Route("/messages/{id}", name="conversation")
     */

    public function conversation($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //cherche l'user de la session
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Groups::class);
        $groups = $repository->findAll();
        //1 : Récupérer les infos du post
        $groupe = $this->getDoctrine()->getRepository(Groups::class)->find($id);
    
        // Form de la création du message
        $manager = $this -> getDoctrine() -> getManager();

        $messageForm = new Message();

        $form = $this -> createForm(MessageFormType::class, $messageForm);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()) {
            $manager -> persist($messageForm);
            $messageForm->setDatetime(new \DateTime('now'));
            $messageForm->setState(1);
            $messageForm->SetGroupe($groupe);
            $messageForm->SetUser($user);
            $manager -> flush();
            
            return $this -> redirectToRoute('conversation', ['id' => $id]);
        }


        //2 : Afficher la vue avec les infos
        return $this->render('messages/index.html.twig', array('groupe' => $groupe, 'groups' => $groups, 'messageForm' => $form-> createView()));
    }
}
