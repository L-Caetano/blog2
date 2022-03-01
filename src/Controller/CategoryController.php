<?php
namespace App\Controller;
use App\Entity\Category;
use App\Entity\Postagem;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\PostType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use PhpParser\Node\Expr\Cast\Object_;

/**
 * @Route("/album", name="album.")
 */
class CategoryController extends AbstractController{
    
    /**
     * @Route("/todos", name="albuns")
     */
    public function index(PaginatorInterface $paginator,Request $request){
        $cat = $this->getDoctrine()->getRepository(Category::class)->findBy(['public'=>true]);
        $em = $this->getDoctrine()->getManager();
        foreach($cat as $c){
            $postagemnsGet= $em->getRepository(Postagem::class)->createQueryBuilder('a')
            ->select('a.imagem')
            ->join('a.categories', 'c')
            ->where('c.id = :id')
            ->orderBy('a.id' ,'DESC')
            ->setParameter('id', $c->getId())
            ->setMaxResults( 1 )
            ->getQuery()
            ->getResult();
            if(!empty($postagemnsGet)){
                $postagemnsGet= (Object) $postagemnsGet[0];
                $postagemImg[$c->getId()]= $postagemnsGet->imagem;
            }else{
                $postagemImg[$c->getId()]= null;
            }
            
        }
        $postagem = $this->getDoctrine()->getRepository(Postagem::class)->findAll();
        $categories = $paginator->paginate(
         $cat, $request->query->getInt('page',1),6);
         //dd($categories);
         //dd($categories->items);
        return $this->render('albuns/index.html.twig', [
            'album' => $categories,
            'imagem' => $postagem,
            'postagemImg' => $postagemImg,
        ]);
    }
     /**
     * @Route("/meusAlbuns", name="MeusAlbuns")
     */
    public function meusAlbuns(PaginatorInterface $paginator,Request $request){
        $user = $this->getUser();
        $cat = $this->getDoctrine()->getRepository(Category::class)->findBy(array('usuario' => $user));
        $em = $this->getDoctrine()->getManager();
        foreach($cat as $c){
            $postagemnsGet= $em->getRepository(Postagem::class)->createQueryBuilder('a')
            ->select('a.imagem')
            ->join('a.categories', 'c')
            ->where('c.id = :id')
            ->orderBy('a.id' ,'DESC')
            ->setParameter('id', $c->getId())
            ->setMaxResults( 1 )
            ->getQuery()
            ->getResult();
            if(!empty($postagemnsGet)){
                $postagemnsGet= (Object) $postagemnsGet[0];
                $postagemImg[$c->getId()]= $postagemnsGet->imagem;
            }else{
                $postagemImg[$c->getId()]= null;
            }
            
        }
        // dd($cat);
        //echo 'testeeeeeeeeeeee';
        //$image = [];
        //foreach ($cat as $category){
        // dd($category->getPostagem());
        //array_push($image, $this->getDoctrine()->getRepository(Postagem::class)->findOneBy(['categories' => $cat]));
            // dd($category);
        //}
        // dd($image);
        $categories = $paginator->paginate(
        $cat, $request->query->getInt('page',1),6);
        //dd($categories->items);
        //dd($image);
        
        return $this->render('albuns/meusAlbuns.html.twig', [
            'album' => $categories,
            'imagem' => $postagemImg,
        ]);
  }
    /**
     * @Route("/album/salvaAlbum", name="api_post_album", methods={"POST"}))
     */
    public function postAlbumAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $images = $request->request->get('images');
        $request->request->get('album');
        /** @var User $user */
        $user = $this->getUser();
        //dd($request->request->get('album'));
        //Acha a category  pelo id
        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('album'));
        if(is_array($images)){
            foreach($images as $image){
                $post = $em->getRepository(Postagem::class)->find($image);
                $category->addPostagem($post);
                $em->persist($category);
                $em->flush();
            }

        }
        return new JsonResponse(array('success' => true));
    }

    /**
     * @Route("/album/create", name="create")
     */
    public function postAlbumCreateAction(Request $request){
        $album = new Category();
        $user = $this->getUser();
        $album->setUsuario($user);
        //verifica se o usuario é admin
        $role= $user->getRoles();
                if($role[0] == 'ROLE_ADMIN'){
                    $album->setPublic(true);

                }
                else{
                    $album->setPublic(false);
                }
        $album->setName($request->request->get('name'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($album);
        $em->flush();
        return $this->redirectToRoute('album.albuns');
    }
    /**
     * @Route("/album/ajax", name="getAlbums")
     */
    public function getAlbumsAction(Request $request)
    {
        $user = $this->getUser();
        $a = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(array('usuario' => $user));

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();
            $idx = 0;
            foreach ($a as $arg) {
                $temp = array(
                    'id' => $arg->getId(),
                    'name' => $arg->getName(),
                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
        }
    }
    /**
     * @Route("/view/{id}", name="view")
     */
    public function viewAlbumAction(Category $cat, PaginatorInterface $paginator,Request $request){
      //$em=$this->getDoctrine()->getManager();
        //cria querybuilder para achar por cat
        // $query = $this->getDoctrine()->getRepository(Category::class)->createQueryBuilder('a')
        // ->innerJoin('a.postagem', 'c', 'WITH', 'c.categories = :id')
        // ->setParameter('id', $cat->getId())->getQuery()->getResult();
        //$catRepo= $em->getRepository(Category::class)->getPostagens($cat->getId());
        //dd($catRepo);
        $category = $paginator->paginate(
        $cat->getPostagem(), $request->query->getInt('page',1),16);
        // $category->name = $cat->name;
        // dd($category,$cat,$em);
        return $this->render('albuns/view.html.twig', [
        'albumInfo' => $cat,
        'album' => $category,
        ]);
    }

    //endpoint para remover as categorias e tirar as postagens
    /**
     * @Route("/album/delete", name="delete", methods={"DELETE"})
     */
    public function deleteRemoveAlbumAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository(Category::class)->find($request->get('id'));
        if(!$album){
            return new JsonResponse(array('success' => false, 'message' => 'Album não encontrado'));
        }
        foreach($album->getPostagem() as $postagem){
            $album->removePostagem($postagem);
        }
        $em->remove($album);
        $em->flush();
        return new JsonResponse(array('success' => true));
    }
    //remove as imagens do album
    /**
     * @Route("/album/remove_imagem", name="remove_imagem", methods={"PUT"})
     */
    public function removeImagemAlbumAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ids = $request->request->get('id');
        //para cada id procura uma postagem
        foreach($ids as $id){
            $postagem = $em->getRepository(Postagem::class)->find($id);
            //se a postagem existir remove ela
            if($postagem){
                if(!$postagem){
                    return new JsonResponse(array('success' => false, 'message' => 'Postagem não encontrada'));
                }
                $album = $postagem->getCategories();
                if(!$album->removePostagem($postagem)){
                    return new JsonResponse(array('success' => false, 'message' => 'Erro ao remover a imagem'));
                }
            }
        }
        return new JsonResponse(array('success' => true, 'message' => 'Imagens removidas com sucesso'));
    }





}