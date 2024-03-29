<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Notice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class LayoutController extends Controller
{
    /**
     * @Route("/menu", methods={"GET"}, name="menu")
     */
    public function showMenuAction(UserInterface $user)
    {
        $userId = $user->getId();
        $userName = $user->getUsername();
        return $this->render('AppBundle:LayoutController:first_menu.html.twig', [
            'id' => $userId,
            'username' => $userName,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function showFirstPageAction(UserInterface $user = null)
    {
        if($user === null){
            return $this->render('AppBundle:LayoutController:first_menu.html.twig');
        }
        $userName = $user->getUsername();
        $userId = $user->getId();
        return $this->render('AppBundle:LayoutController:first_menu.html.twig', [
            'username' => $userName,
            'id' => $userId,
        ]);
    }

}
