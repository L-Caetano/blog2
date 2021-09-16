<?php

namespace App\Controller;

use App\Entity\Postagem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/",name="blog_homepage")
     */
    public function homepage()
    {
        return $this->render('postagem/homepage.html.twig');
    }
    /**
     * @Route("/postagem/{slug}", name="blog_postagem")
     */
    public function postagem($slug)
    {
      $rep = $this->getDoctrine()->getRepository(Postagem::class);
      $postagem = $rep->find($slug);
       return $this->render('postagem/postagem.html.twig', [
                'postagem' => $postagem
        ]);
    }

}