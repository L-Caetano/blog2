<?php

namespace App\Controller;
use App\Entity\Category;
use App\Entity\Postagem;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/postagem", name="blog_postagem.")
 */
class PostagemController extends AbstractController
{
    public $cat;
    /**
     * @Route("/view/{id}", name="index")
     */
    public function postagemView($id)
    {
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findBy(array('id' => $id));
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

        $form = $this->createForm(PostType::class, $postagem);
        $form->handleRequest($request);
        if($form->isSubmitted()){
           //dd($postagem);
            $em = $this->getDoctrine()->getManager();
            //dd($request->files->get('postagem'));
            /** @var UploadedFile $file */
            $file = $request->files->get('post')['imagem'];
            if($file){
                $filename = md5(uniqid()).'.'.$file->guessClientExtension();
                $file->move(
                    $this->getParameter('uploads_dir'),
                        $filename
                );
                $postagem->setImagem($filename);
                $postagem->setUsuario($this->getUser());
                $session = new Session();
                $postagem->setAutor($session->get('_security.last_username'));
            }
            $em->persist($postagem);
            $em->flush();
            $this->addFlash('sucesso', 'Postagem Criada');
            return $this->redirect($this->generateUrl('blog_postagem.list'));
        }
        return $this->render('postagem/create.html.album.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/list", name="list")
     */
    public function postagemList(){
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findAll();
       // $rep = $this->getDoctrine()->getRepository(Category::class);
      // $cat = $rep->findAll();
        //dd($cat);
        return $this->render('postagem/postagemList.html.twig',[
            'postagens' => $postagem,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function removePost($id){

        $en = $this->getDoctrine()->getManager();
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findBy(array('id' => $id));
        $en->remove($postagem[0]);
        $en->flush();

        $this->addFlash('sucesso', 'Postagem deletada');
        return $this->redirect($this->generateUrl('blog_postagem.list'));
    }
    /**
     * @Route("/category/{id}", name="category")
     */
    public function filterByChategory($id){
        $rep = $this->getDoctrine()->getRepository(Postagem::class);
        $postagem = $rep->findBy(array('category' => $id));
        $rep = $this->getDoctrine()->getRepository(Category::class);
        $this->cat = $rep->findAll();
        return $this->render('postagem/postagemList.html.twig',[
            'postagens' => $postagem,
            'category' =>  $this->cat
        ]);
    }
      /**
     * @Route("/search/{search}", name="Search")
     */
    public function filterBySearch($search){
        $rep[0] = $this->getDoctrine()->getRepository(Postagem::class)->createQueryBuilder('a')
   ->where('a.titulo LIKE :title')
  // ->andWhere('a.category_id == :category')
   ->setParameter(':title', '%'.$search.'%')
   //->setParameter(':category', $id)
   ->getQuery()
   ->getResult();
        //$request->query->get('id');
        //$rep[0] = $rep[0]->findBy(array('titulo' => $search));
        if($this->cat == null){
        $rep[1] = $this->getDoctrine()->getRepository(Category::class);
        $rep[1] = $rep[1]->findAll();
        }else{
            $rep[1] = $this->cat;
        }

        return $rep;
    }

    /** 
     * @Route("/createImagens", name="create_imagens")
     * @param Request $request
     */
    public function createImagens(Request $request){


    }
}
