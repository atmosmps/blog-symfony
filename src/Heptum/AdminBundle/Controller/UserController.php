<?php

namespace Heptum\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository("AdminBundle:Users")->findAll();
        return $this->render('AdminBundle:User:index.html.twig', ['users' => $users]);
    }
}
