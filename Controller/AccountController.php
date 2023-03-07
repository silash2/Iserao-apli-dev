<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("account", name="app_account", methods="GET")
     */
    public function myAccount(): Response
    {
        return $this->render('account/myAccount.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route{"account/edit", name="app_account_edit", methods:{"GET","POSt"}}
     */
    public function edit(EntityManagerInterface $em, Request $request, UserRepository $userRepo):Response
    {
        $form = $this->createForm(UserFormType::class, $this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            
            $em->flush();
            $this->addflash('success', 'profile updated');
            return $this->redirectToRoute('app_account');

        }

        return $this->render('account/edit.html.twig', [
            'form'=>$form->createView()
            ]);
    }
}
