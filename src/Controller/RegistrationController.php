<?php

namespace App\Controller;

use App\Entity\Postagem;
use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
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
    public function dashboard(){
        $en = $this->getDoctrine()->getManager();
        $users = $en->getRepository(User::class)->findAll();
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
            return $this->redirect($this->generateUrl('dashboard'));
        }
        return $this->render('security/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView()

        ]);
    }
    /**
     * @Route("/deleteUser/{id}", name="deleteUser")
     */
    public function removeUser($id){
        $en = $this->getDoctrine()->getManager();
        $rep = $this->getDoctrine()->getRepository(User::class);
        $user = $rep->findBy(array('id' => $id));
        //dd($user[0]);
        $en->remove($user[0]);
        $en->flush();

        $this->addFlash('sucesso', 'User deletado');
        return $this->redirect($this->generateUrl('dashboard'));
    }
}
