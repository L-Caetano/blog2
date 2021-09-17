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
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findBy(array(),array('id' => 'DESC'),3);
        //dd($postagem);
        return $this->render('postagem/homepage.html.twig',[
            'postagens' => $postagem
        ]);
    }
}