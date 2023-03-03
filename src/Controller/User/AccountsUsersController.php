<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountsUsersController extends AbstractController
{
//    #[Route('/login', name: 'app_login', methods: ['GET'])]
//    public function loginUser(UserRepository $userRepository): Response
//    {
//        return $this->render('AccountsUsers/login.html.twig', [
//            'users' => $userRepository->findAll(),
//        ]);
//    }

    #[Route('/register', name: 'app_register')]
    public function registerUser(Request $request, UserPasswordHasherInterface $passwordHasher,UserRepository $userRepository): Response
    {
        if($request->getMethod() === 'POST'){
            $user = new User();
            $user->setPassword($passwordHasher->hashPassword($user,$request->request->get('logpass')));
            $user->setAddress($request->request->get('logadress'));
            $user->setEmail($request->request->get('logemail'));
            $user->setPhone($request->request->get('logphone'));
            $user->setNom($request->request->get('logname'));

            $userRepository->save($user,true);

            return $this->redirectToRoute('app_login');
        }
        return $this->render('AccountsUsers/register.html.twig');
    }
    #[Route('/forgetpass', name: 'pass_forget')]
    public function forgotpass(): Response
    {
        return $this->render('AccountsUsers/forget_password.html.twig');
    }




}
