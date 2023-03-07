<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\User;
use App\Form\PinType;
use App\Entity\Comment;
use App\Entity\PinSearch;
use App\Form\PinSearchType;
use App\Form\CommentFormType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class PinsController extends AbstractController
{
    /**
     * @Route("/home", name="app_home", methods ={"GET", "POST"})
     */
    public function index(PinRepository $pinRepository, Request $request): Response
    {
        
        $pins = $pinRepository->findBy([],['createdAt'=>'DESC']);

        return $this->render('pins/index.html.twig',compact('pins'));
    }


    /**
     * @route("/pins/{id<[0-9]+>}", name= "app_pins_show" )
     */
    public function show(Pin $pin, Request $request, CommentRepository $commentRepository, EntityManagerInterface $em, UserRepository $userRepository, PinRepository $pinrepository):Response
    {
        
      
        //get all comment
        $comments = $commentRepository->findBy([],['createdAt'=>'DESC']);
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
       



        //get the user id and submit the comment's content
        if($form->isSubmitted() && $form->isValid())
        {
            //get the connected user 
            $comment->setUser($this->getUser());
            //get the pin concerned
            $comment->setPins($pin);
            //write it on database
        
            $em->persist($comment);
            $em->flush();    
                
          
                
            //find comment in the table comments and display it by recent creation order
            

            return $this->redirectToRoute('app_pins_show',[
                'id'=>$pin->getId(),
                'pin'=>$pin,
                'comments'=>$comments]);
        }

        return $this->render('pins/show.html.twig', [
            'pin'=>$pin, 
            'comment_form'=>$form->CreateView(),
            
          
            ]);
    }



    /**
     * @Route("/pins/create",name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository):Response
    {   
        $pin= new Pin;
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);
        
        if($form->isSubmitted()&&$form->isValid())
        {
            
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();


            $this->addFlash('success','pin created');

            return $this->redirectToRoute('app_home');

        }
        
        
        return $this->render('pins/create.html.twig', [
            'form'=>$form->createView()]);
    }
    



    /**
     * @Route("pins/{id<[0-9]+>}/edit",  name="app_pins_edit")
     */
    public function edit(EntityManagerInterface $em, Request $request, Pin $pin):Response
    {

        $form =$this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('success', 'pin eddited successfully !!!');
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/edit.html.twig', 
        [
            'pin'=>$pin ,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("pins/{id<[0-9]+>}/delete}", name="app_pins_delete")
     */
    public function delete(Request $request, EntityManagerInterface $em,Pin $pin):Response
    {
        /*if($this->isCsrfTokenValid('pin_delete_'.$pin->getId(),$request->request->get('csrf_token')))
        {
            $em->remove($pin);
            $em->flush(); 
            
            $this->addFlash('danger','pin succesfully deleted !!!'); 
        }*/
        $em->remove($pin);
        $em->flush();
        $this->addFlash('danger', 'pin succesfully deleted !!');

        

        return $this->redirectToRoute('app_home');
    }
}
