<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Route("admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin_menu", name="admin_menu")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showAdminMenuAction(UserInterface $user)
    {
        $adminName = $user->getUsername();
        return $this->render('AppBundle:LayoutController:admin_menu.html.twig', [
            'adminname' => $adminName
        ]);
    }

//    /**
//     * @Route("/user/{username}", name="user_addrole")
//     * @Security("has_role('ROLE_ADMIN')")
//     */
//    public function addRoleToUser($username)
//    {
//        // Retrieve entity manager of doctrine
//        $em = $this->getDoctrine()->getManager();
//
//        // Search for the UserEntity, retrieve the repository
//        $userRepository = $em->getRepository("AppBundle\Entity\User");
//        // or $userRepository = $em->getRepository("myBundle:User");
//
//        $user = $userRepository->findOneBy(["username" => $username]);
//
//        // Add the role that you want !
//        $user->addRole("ROLE_ADMIN");
//
//        // Save changes in the database
//        $em->persist($user);
//        $em->flush();
//
//        return new Response('zmieniono rolę');
//    }


}
