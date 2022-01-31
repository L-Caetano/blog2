<?php
namespace App\Controller;
use App\Entity\Category;
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

/**
 * @Route("/album", name="album.")
 */
class CategoryController extends AbstractController{
    
    /**
     * @Route("/todos", name="albuns")
     */
    public function index(){
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('albuns/index.html.twig', [
            'albuns' => $categories
        ]);
    }

}