<?php

namespace App\Controller\Admin;

use App\Entity\User;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserController extends AbstractController
{

    /**
     * @Route("/admin/createuser", name="admin_create_user")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setRoles(["ROLE_USER"]);

            // On récupère le password entré dans le formulaire.
            $plainpassword = $userForm->get('password')->getData();

            // Hashage du password
            $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $plainpassword);
            $user->setPassword($hashedPassword);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/form_user.html.twig', ['userForm' => $userForm->createView()]);
    }
}
