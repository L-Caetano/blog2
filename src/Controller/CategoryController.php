<?php
namespace App\Controller;
use App\Entity\Category;
use App\Entity\Postagem;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\PostType;
use App\Form\UserType;
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
/**
 * @Route("/album", name="album.")
 */
class CategoryController extends AbstractController{
    
    /**
     * @Route("/todos", name="albuns")
     */
    public function index(PaginatorInterface $paginator,Request $request){
        $cat = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $categories = $paginator->paginate(
         $cat, $request->query->getInt('page',1),6);
         //dd($categories->items);
         
        return $this->render('albuns/index.html.twig', [
            'album' => $categories,
        ]);
    }
    
    /**
     * @Route("/view/{id}", name="view")
     */
    public function viewAlbumAction(Category $cat, PaginatorInterface $paginator,Request $request){
        $em=$this->getDoctrine()->getManager();
        $category = $paginator->paginate(
        $cat->getPostagem(), $request->query->getInt('page',1),16);
      // $category->name = $cat->name;
        // dd($category,$cat,$em);
        return $this->render('albuns/view.html.twig', [
         'albumInfo' => $cat,
         'album' => $category,
        ]);
    }
/** 
   * @Route("/album/ajax", name="getAlbums") 
*/ 
public function getAlbumsAction(Request $request) {  
    $a = $this->getDoctrine() 
       ->getRepository(Category::class) 
       ->findAll();  
       
    if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
       $jsonData = array();  
       $idx = 0;  
       foreach($a as $arg) {  
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
   * @Route("/album/salvaAlbum", name="api_post_album", methods={"POST"})) 
*/ 
public function postAlbumAction(Request $request) {  
    $em = $this->getDoctrine()->getManager();
      $images = $request->request->get('images');
      $request->request->get('album');
      /** @var User $user */
      $user = $this->getUser();
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
         $album->setName($request->request->get('name'));
         $user = $this->get('security.context')->getToken()->getUser();
         $album->setUsuario($user);
         $em = $this->getDoctrine()->getManager();
         $em->persist($album);
         $em->flush();
         return $this->redirectToRoute('album.albuns');
      }


}