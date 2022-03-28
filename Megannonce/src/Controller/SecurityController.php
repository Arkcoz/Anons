<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Personne;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{

    #[Route('/inscription', name: 'security_registration')]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher){
        $user = new Personne();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
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
                $user->setImage($img);
            }
            
            //Date de création = maintenant
            $user->setCreatedAt(new \DateTime());

            //Adresse mail admin
            if($user->getEmail() == "root@gmail.com"){
                $user->setIsAdmin(true);
            } else{
                $user->setIsAdmin(false);
            }

            //Partie hachage du mdp
            $hashedPassword = $passwordHasher->hashPassword(
                $user, 
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);

            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView(),
            'title' => "Créez un compte"
        ]);
    }

    #[Route('/connexion', name :"security_login")]
    public function login(){
        return $this->render('security/login.html.twig', [
            'title' => "Connectez-vous !"
        ]);
    }

    #[Route('/deconnexion', name :"security_logout")]
    public function logout(){
        //Rien = déconnexion
    }
    
    
}
