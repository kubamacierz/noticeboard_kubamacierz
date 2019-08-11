<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class LayoutController extends Controller
{
    /**
     * @Route("/menu", methods={"GET"})
     */
    public function showMenuAction()
    {
        return $this->render('AppBundle:LayoutController:show_menu.html.twig', []);
    }

}
