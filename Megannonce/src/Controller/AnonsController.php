<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Annonce;
use App\Entity\Personne;
use App\Form\ModifyType;
use App\Form\AddAnnoncesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnonsController extends AbstractController
{
    
    public function __construct(private ManagerRegistry $doctrine, private Security $security){

    }

    #[Route('/anons', name: 'anons')]
    public function index(): Response{
        //renvoie la page
        return $this->render('anons/index.html.twig', [
            'controller_name' => 'AnonsController',
        ]);
    }

    #[Route('/', name :"home")]
    public function home(){
        //renvoie la page
        return $this->render('anons/home.html.twig', [
            'title' => "Bienvenue sur Anons !",
        ]);
    }

    #[Route('/ajouter_annonce', name :"addAnnonce")]
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->security->getUser();
        $annonce = new Annonce();
        $form = $this->createForm(AddAnnoncesType::class, $annonce);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            //On récupère l'image de profil upload
            $images = $form->get('images')->getData();
            //Création du fichier
            if($images != null) {
                foreach($images as $image){
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();
                    //On met l'image dans le dossier upload
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    //Stockage dans la BDD
                    $img = new Images();
                    $img->setName($fichier);
                    $annonce->addImage($img);
                }
                
            }
            
            //Date de création = maintenant
            $annonce->setCreatedAt(new \DateTime());

            //Le créateur
            $annonce->setPersonne($user);

            //Date de création = maintenant
            $annonce->setIsVerify(false);

            $entityManager->persist($annonce);
            $entityManager->flush();
            
            return $this->redirectToRoute('annonce', ['id'=>$annonce->getId()]);

        }

        //renvoie la page
        return $this->render('anons/addAnnonce.html.twig', [
            'form' => $form->createView(),
            'title' => "Ajouter une annonce",
            'user' => $this->security->getUser()
        ]);
    }


    
    #[Route('/rechercher_annonce', name :"searchAnnonce")]
    public function searchAnnonce(){
        //renvoie la page
        return $this->render('anons/searchAnnonce.html.twig', [
            
        ]);
    }



    #[Route('/mes_annonces', name :"mesAnnonces")]
    public function mes_annonces(){
        //Limiter l'accès au anonyme
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //renvoie la page
        return $this->render('anons/mesAnnonces.html.twig', [
            'title' => "Mes annonces",
            'user' => $this->security->getUser(),
        ]);
    }



    //VOIR MON PROFIL
    #[Route('/mon_profil', name :"mon_profil")]
    public function mon_profil(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        //renvoie la page
        return $this->render('anons/profil.html.twig', [
            'title' => "Mon Profil",
            'user' => $this->security->getUser(),
        ]);
    }



    //VOIR PROFIL D'UN AUTRE UTILISATEUR
    #[Route('/profil/{id}', name :"profil")]
    public function show_profil($id): Response{
        
        $repo = $this->doctrine->getRepository(Personne::class);
        $user = $repo->find($id);
        
        //Si tu es connecté
        if($this->security->getUser() != null){

            //Et que le profil que tu veux voir c'est le tien
            if($this->security->getUser()->getId() == $id){
                return $this->redirectToRoute('mon_profil');
            }
        }

        if($user == null){
            return $this->redirectToRoute('error');
        }
        
        //renvoie la page
        return $this->render('anons/profil.html.twig', [
            'title' => "Autre Profil",
            'user' => $user,
        ]);
    }



    //MODIFIER MON PROFIL
    #[Route('/modifier_mon_profil', name :"modify_profil")]
    public function modify_profil(Request $request, EntityManagerInterface $entityManager): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->security->getUser();
        $form = $this->createForm(ModifyType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            //On récupère l'image de profil upload
            $image = $form->get('image')->getData();
            //Création du fichier
            if($image != null) {
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                //On met l'image dans le dossier upload
                $image->move($this->getParameter('images_directory'),$fichier);
                //Stockage dans la BDD
                $img = new Images();
                $img->setName($fichier);
                if($user->getImage() != null){
                    $entityManager->remove($user->getImage());
                }
                $entityManager->flush();
                $user->setImage($img);
                $entityManager->persist($img);
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('mon_profil');
        }

        //renvoie la page
        return $this->render('anons/modify_profil.html.twig', [
            'title' => "Modification de Profil",
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    
    


    #[Route('/annonce/{id}', name :"annonce")]
    public function show_annonce($id): Response{
        $repo = $this->doctrine->getRepository(Annonce::class);
        $annonce = $repo->find($id);
        
        //renvoie la page
        return $this->render('anons/annonce.html.twig', [
            'annonce' => $annonce, 
        ]);
    }

    #[Route('/erreur_404', name :"error")]
    public function show_error(): Response{
        
        
        //renvoie la page
        return $this->render('anons/error.html.twig', [

        ]);
    }

    #[Route('/modifier_annonce/{id}', name :"modify_annonce")]
    public function modify_annonce($id): Response{
        
        //Pas encore coder
        return $this->redirectToRoute('error');
    }

    #[Route('/delete_annonce/{id}', name :"delete_annonce")]
    public function delete_annonce($id, EntityManagerInterface $entityManager): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        
        $repo = $this->doctrine->getRepository(Annonce::class);
        $annonce = $repo->find($id);
        if ($annonce->getPersonne() == $this->security->getUser()){
            $this->security->getUser()->removeAnnonce($annonce);
            $entityManager->remove($annonce);
            $entityManager->flush();
        }
        

        return $this->redirectToRoute('mon_profil');
        
    }

}
