<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/boutique")
 */
class BoutiqueController extends AbstractController
{
    /**
     * @Route("/", name="app_boutique_index", methods={"GET"})
     */
    public function index(BoutiqueRepository $boutiqueRepository, PinRepository $pinRepository): Response
    {
        
        $boutiques = $boutiqueRepository->findBy([], ['createdAt'=>'DESC']);
        return $this->render('boutique/index.html.twig', [
            'boutiques' => $boutiques
        ]);
    }

    /**
     * @Route("/new", name="app_boutique_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BoutiqueRepository $boutiqueRepository, UserRepository $userRepository): Response
    {
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setProprietaire($this->getUser());
            $boutiqueRepository->add($boutique, true);
            

            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boutique/new.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    //affiche les publications relier aux boutiques
    /**
     * @Route("/{id}", name="app_boutique_show", methods={"GET"})
     */
    public function show(BoutiqueRepository $boutiqueRepository, PinRepository $pinRepository, int $id): Response
    {
        //donne id au boutique et lui attribue son identifiant
        $boutique= $boutiqueRepository->find($id);

        //cherche les pins dans boutique respository.
        $pins = $boutique->getPublication();

        return $this->render('boutique/show.html.twig', [

            'pins'=>$pins,
            'boutique'=>$boutique           
            
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_boutique_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutiqueRepository->add($boutique, true);

            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boutique/edit.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_boutique_delete", methods={"POST"})
     */
    public function delete(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boutique->getId(), $request->request->get('_token'))) {
            $boutiqueRepository->remove($boutique, true);
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }

    //publie et donne id boutique aux pins
    /**
     * @Route("/{id}/publication", name ="app_boutique_publication")
     */
    public function publication(Request $request, EntityManagerInterface $em, UserRepository $userRepository, BoutiqueRepository $boutiqueRepository, int $id):Response
    {
        $boutique = $boutiqueRepository->find($id);
        $pin= new Pin;
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);
        
        if($form->isSubmitted()&&$form->isValid())
        {
            
            $pin->setUser($this->getUser());
            $pin->setBoutique($boutique);
        
            $em->persist($pin);
            $em->flush();


            $this->addFlash('success','publication created');
            return $this->redirectToRoute('app_boutique_show',[
                'id'=>$boutique->getId(),
                'boutique'=>$boutique,
                'pin'=>$pin            ] );
        }

        return $this->render('pins/create.html.twig', [
            'form'=>$form->createView(),
            'boutique'=>$boutique  
            
                    
            
        ]);
    }

    /**
     * @Route("/{id}/mesBoutiques", name ="app_boutique_user")
     */
    public function mesBoutique(UserRepository $userRepository, BoutiqueRepository $boutiqueRepository, int $id)
    {
        $user = $userRepository->find($id);
        $boutiques = $user->getBoutiques();

        return $this->render('boutique/index.html.twig', [
            'user'=>$user,
            'boutiques'=>$boutiques,
        ]);

    }

}
