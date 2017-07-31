<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class PostController
 * @package AppBundle\Controller
 * @Route("/posts")
 */
class PostController extends Controller
{
    /**
     * @return Response
     * @Route("/")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository("AppBundle:Post")->findAll();
        return $this->render('posts/posts.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $post = ($form->getData());
            $post->setCreatedAt(new \DateTime("now", new \DateTimeZone('America/Sao_Paulo')));
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone('America/Sao_Paulo')));

            $doctrine = $this->getDoctrine()->getManager();

            $doctrine->persist($post);
            $doctrine->flush();

            return $this->redirect('/posts');
        }

        return $this->render('posts/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function editAction($id)
    {
        $post = $this->getDoctrine()->getRepository("AppBundle:Post")->find($id);

        $form = $this->createForm(PostType::class, $post);

        if ($form->isValid() && $form->isSubmitted()) {
            $post = ($form->getData());
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone('America/Sao_Paulo')));

            $doctrine = $this->getDoctrine()->getManager();

            $doctrine->persist($post);
            $doctrine->flush();

            return $this->redirect('/posts');
        }

        return $this->redirect('/posts/edit/' . $id);
    }

    /**
     * @Route("/{slug}")
     */
    public function singleAction($slug)
    {
        return $this->render('posts/single.html.twig', ['slug' => $slug]);
    }
}
