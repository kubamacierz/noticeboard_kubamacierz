<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class LayoutController extends Controller
{
    /**
     * @Route("/menu", methods={"GET"})
     */
    public function showMenuAction(UserInterface $user)
    {
        $userId = $user->getId();
        return $this->render('AppBundle:LayoutController:show_menu.html.twig', ['id' => $userId]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function showFirstPageAction(UserInterface $user)
    {
        $userName = $user->getUsername();
        return $this->render('AppBundle:LayoutController:first_menu.html.twig', ['username' => $userName]);
    }

}
