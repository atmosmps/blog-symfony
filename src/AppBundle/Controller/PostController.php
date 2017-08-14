<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("has_role('ROLE_ADMIN')")
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

            $this->addFlash("success", "Post inserido com sucesso");

            return $this->redirect('/posts');
        }

        return $this->render('posts/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Post $post, Request $request)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $post = ($form->getData());
            $post->setUpdatedAt(new \DateTime("now", new \DateTimeZone('America/Sao_Paulo')));

            $doctrine = $this->getDoctrine()->getManager();

            $doctrine->persist($post);
            $doctrine->flush();

            $this->addFlash("success", "Post editado com sucesso");

            return $this->redirect('/posts/edit/' . $post->getId());
        }

        return $this->render('posts/create.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @param Post $post
     * @Route("/remove/{post}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAction(Post $post)
    {
        $this->getDoctrine()->getManager()->remove($post);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash("warning", "Post removido com sucesso");

        return $this->redirect('/posts');
    }

    /**
     * @Route("/{slug}")
     */
    public function singleAction($slug)
    {
        return $this->render('posts/single.html.twig', ['slug' => $slug]);
    }
}
