<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\PostagemRepository;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
     /**
     * @route("/search",name="search")   
     *  @param PostRespository $postRepository
     *  @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    
    public function searchBar(){
        $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('handleSearch'))
        ->add('Pesquisa', TextType::class)
        ->add('Pesquisar', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ])
        ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/handleSearch", name="handleSearch") 
     * @param Request $request
     */
    public function handleSearch(Request $request, PostagemController $postagemController){ 
        $rep = $postagemController->filterBySearch($request->request->get('form')['Pesquisa']);
        //var_dump($request->query->get('id')); die;
       return $this->render('postagem/postagemList.html.twig',[
        'postagens' => $rep[0],
        'category' => $rep[1]
    ]);
        }
}
