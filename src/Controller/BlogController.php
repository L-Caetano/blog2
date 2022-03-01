<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Postagem;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/",name="blog_homepage")
     */
    public function homepage(PaginatorInterface $paginator,Request $request)
    {
        $cat = $this->getDoctrine()->getRepository(Category::class)->createQueryBuilder('a')
        ->select('a.name,a.id ')
        ->where('a.public = 1')
        ->orderBy('a.creation_date' ,'DESC')
        ->setMaxResults( 5 )
        ->getQuery()
        ->getResult();
        $em = $this->getDoctrine()->getManager();
        foreach($cat as $c){
            //dd($c);
            $postagemnsGet= $em->getRepository(Postagem::class)->createQueryBuilder('a')
            ->select('a.imagem')
            ->join('a.categories', 'c')
            ->where('c.id = :id')
            ->orderBy('a.id' ,'DESC')
            ->setParameter('id', $c['id'])
            ->setMaxResults( 1 )
            ->getQuery()
            ->getResult();
            if(!empty($postagemnsGet)){
                $postagemnsGet= (Object) $postagemnsGet[0];
                $postagemImg[$c['id']]= $postagemnsGet->imagem;
            }else{
                $postagemImg[$c['id']]= null;
            }
            
        }
        $categories = $paginator->paginate(
         $cat, $request->query->getInt('page',1),6);
         //dd($categories->items);
        return $this->render('postagem/homepage.html.twig', [
            'album' => $categories,
            'imagem' => $postagemImg
        ]);
    }
}