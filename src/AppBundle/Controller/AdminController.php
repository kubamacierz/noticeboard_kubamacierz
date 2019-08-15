<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/user", name="user_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showAdminMenuAction()
    {
        return $this->render(':user:index.html.twig', []);
    }

    /**
     * @Route("/user/{username}", name="user_addrole")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addRoleToUser($username)
    {
        // Retrieve entity manager of doctrine
        $em = $this->getDoctrine()->getManager();

        // Search for the UserEntity, retrieve the repository
        $userRepository = $em->getRepository("AppBundle\Entity\User");
        // or $userRepository = $em->getRepository("myBundle:User");

        $user = $userRepository->findOneBy(["username" => $username]);

        // Add the role that you want !
        $user->addRole("ROLE_ADMIN");

        // Save changes in the database
        $em->persist($user);
        $em->flush();

        return new Response('zmieniono rolę');
    }


}
