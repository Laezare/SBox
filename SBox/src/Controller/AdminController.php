<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    

    /**
     * @Route("/register", name="sign_up")
     */
    public function register(Request $request,  UserPasswordEncoderInterface $encoder)
    {
        $manager = $this -> getDoctrine() -> getManager();

        $user = new User();

        $form = $this -> createForm(RegisterType::class, $user);

        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()) {
            $passwordEncoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordEncoded);
            $manager -> persist($user);
            $manager -> flush();
            
            $this -> addFlash('alert', 'success Le User a bien été enregisté');
            return $this -> redirectToRoute('app_login');
        }

        return $this -> render('admin/register.html.twig', [
            'registerForm' => $form -> createView()
        ]);
    }


    /**
     * @Route("/loginCheck", name="login_check") 
     * route nécessaire pour le fonctionnement de securité de ma connexion de SF
     */

    public function loginCheck()
    {}

    /**
     * @Route("/", name="home") 
     * route nécessaire pour le fonctionnement de securité de ma connexion de SF
     */

    public function homeRedirect()
    {
        return $this -> redirectToRoute('app_login');
    }
}
