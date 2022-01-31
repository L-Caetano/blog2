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

class RegistrationController extends AbstractController
{ 
    
    /**
     * @Route("/deleteUser/{user}", name="deleteUser")
     */
    public function removeUserAction(User $user){
       // dd($user);
       //dd($user);  
        $en = $this->getDoctrine()->getManager();
       // $rep = $this->getDoctrine()->getRepository(User::class);
        //$user = $rep->find($id);
        //dd($user[0]);
        $en->remove($user);
        $en->flush();

        $this->addFlash('sucesso', 'User deletado com sucesso!!');
        return $this->redirect($this->generateUrl('dashboard'));
    }
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required'=> true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('Register', SubmitType::class)
        ->getForm()
        ;
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();
            //dd($data);
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
            );
            $role[0] = 'ROLE_ADMIN';
            if($data['username'] == 'admin'){
                $user->setRoles($role);
            }
            //dd($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('app_login'));
        }

        return $this->render('registration/index.html.twig', [
                'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Request $request){
        $en = $this->getDoctrine()->getManager();
        
        if($request->isMethod("POST")){
            $userSearch = $request->get('userSearch');
            $users = $en->getRepository(User::class)->createQueryBuilder('a')
   ->where('a.username LIKE :username')
  // ->andWhere('a.category_id == :category')
   ->setParameter(':username', '%'.$userSearch.'%')
   //->setParameter(':category', $id)
   ->getQuery()
   ->getResult();

        }else{
              $users = $en->getRepository(User::class)->findAll();
        }
     
        return $this->render('security/dashboard.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/edituser/{id}", name="user.edit")
     */
    public function editUserAction(Request $request,User $user) {
        $em = $this->getDoctrine()->getManager();
        //$rep = $this->getDoctrine()->getRepository(User::class);
        //$user = $rep->findBy(array('id' => $id_user));
        //dd($user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //$this->addFlash('success', 'section.backoffice.users.edit_roles.confirmation');
           // dd($user);
            $em->persist($user);
            $em->flush();
            $this->addFlash('sucesso', 'User editado com sucesso!');
            return $this->redirect($this->generateUrl('dashboard'));
        }
        return $this->render('security/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView()

        ]);
    }
      /**
     * @Route("/createCat", name="createCategory")
     */
    public function createCat(Request $request){
        $categoria = new Category();

        $form = $this->createForm(CategoryType::class, $categoria);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            //dd($postagem);
            $em = $this->getDoctrine()->getManager();
            $categoria->setCreationDate(new \DateTime());
            $categoria->setUpdateDate(new \DateTime());
            $em->persist($categoria);
            $em->flush();
            $this->addFlash('sucesso', 'Category Criada');
            return $this->redirect($this->generateUrl('blog_postagem.list'));
        }
        return $this->render('postagem/create.html.cat.twig',[
            'form' => $form->createView()
        ]);
    }
  
  
  
}
