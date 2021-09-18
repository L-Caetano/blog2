<?php

namespace App\Controller;

use App\Entity\Postagem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/postagem", name="blog_postagem.")
 */
class PostagemController extends AbstractController
{

    /**
     * @Route("/id/{slug}", name="index")
     */
    public function postagemView($slug)
    {
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findBy(array('id' => $slug));
        //dd($postagem);
        return $this->render('postagem/postagem.html.twig',[
            'postagem' => $postagem[0]
        ]);
    }
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request){
        $postagem = new Postagem();
        $postagem->setTitulo('Teste');
        $postagem->setDescricao('Teste2');
        $postagem->setImagem('Teste3');
        $postagem->setAutor('Caetano');
        $em = $this->getDoctrine()->getManager();
        $em->persist($postagem);
        $em->flush();
    }
    /**
     * @Route("/list", name="list")
     */
    public function postagemList(){
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findAll();
        return $this->render('postagem/postagemList.html.twig',[
            'postagens' => $postagem
        ]);
    }

}
