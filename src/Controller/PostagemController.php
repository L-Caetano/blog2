<?php

namespace App\Controller;
use App\Entity\Category;
use App\Entity\Postagem;
use App\Form\PostType;
use App\Service\OtimizadorImagemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function create(Request $request,OtimizadorImagemService $ois){
        $postagem = new Postagem();

        $form = $this->createForm(PostType::class, $postagem);
        $form->handleRequest($request);
        $valid_ext = array('png','jpeg','jpg');
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
              // file extension
          
            /** @var UploadedFile $file */
            $file = $request->files->get('post')['imagem'];
            //pega toda a data do request
            $data = $request->request->get('post');
            if($file){
                $titulo = $file->getClientOriginalName();
                $file_extension = $file->guessClientExtension();
                $file_extension = strtolower($file_extension);
                if(!in_array($file_extension, $valid_ext)){
                    $this->addFlash('Erro', 'Erro na postagem');
                    return new JsonResponse('Extensão inválida');
                }

                $filename = $titulo;
                $file->move(
                    $this->getParameter('uploads_dir'),
                        $filename
                );
  
                $postagem->setImagem($filename);
                $postagem->setUsuario($this->getUser());
                $postagem->setTitulo($titulo);
                //for each para cada categoria que vier
                foreach($request->request->get('post')['categories'] as $cat){
                    /** @var Category $categoria */
                    $categoria= $this->getDoctrine()->getRepository(Category::class)->find($cat);
                    $categoria->addPostagem($postagem);
                    //dd($categoria);
                    $postagem->addCategory($categoria);
                    
                    $em->persist($categoria);
                }
                $session = new Session();
                $postagem->setAutor($session->get('_security.last_username'));
                
            }
            $em->persist($postagem);
            $em->flush();
            $ois->resize($this->getParameter('uploads_dir').$filename);
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

    //Deletar imagens 
    /**
     * @Route("/deleteImagens/{id}", name="delete_imagens", methods={"DELETE"})
     */
    public function deleteImagens(Request $request){
        $em = $this->getDoctrine()->getManager();
        //para cada id acha uma postagem
        $ids=$request->request->get('id');
        foreach($ids as $id){
            $postagem = $this->getDoctrine()->getRepository(Postagem::class)->find($id);
            if(!$postagem){
                throw $this->createNotFoundException('Imagem não encontrada');
            }
            //tira os albuns da postagem
            foreach($postagem->getCategories() as $album){
                $postagem->removeCategory($album);
            }
            $em->remove($postagem);
        }
      
        $em->flush();
        $this->addFlash('sucesso', 'Postagem deletada');
        return $this->redirect($this->generateUrl('blog_postagem.index'));
    }
}
