<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages/", name="app_messages")
     */
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    
    /**
     * @Route("/send", name="app_messages_send")
     */
    public function send(Request $request, EntityManagerInterface $em): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $message->setUser($this->getUser());
            $em->persit($message);
            $em->flush();

            return $this->redirectToRoute('app_messages', compact('message'));
        }

        return $this->render('messages/send.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/received", name="app_messages_received")
     */
    public function received(): Response
    {



        return $this->render('messages/received.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }
    
    /**
     * @Route("/read/{id}", name="app_messages_read")
     */
    public function read(Messages $message, EntityManagerInterface $em): Response
    {

        $message->setIsRead(true);
        $em->persist($message);
        $em->flush();


        return $this->render('messages/read.html.twig', compact('message'));
    }

    
    /**
     * @Route("/delete/{id}", name="app_messages_delete")
     */
    public function delete(Messages $message, EntityManagerInterface $em): Response
    {

     
        $em->remove($message);
        $em->flush();


        return $this->redirectToRoute('app_received');
    }


}
 