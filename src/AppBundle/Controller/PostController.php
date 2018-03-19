<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Post;




class PostController extends Controller

{

    /**
     * @Route("/post", name="viewpage")
     */
    public function viewAction(Request $request)
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        // replace this example code with whatever you need
        return $this->render('Pages/index.html.twig', [
          'posts'=>$posts
        ]);
    }





    /**
     * @Route("/post/create", name="createpage")
     */
    public function createAction(Request $request)
    {
        $post=new Post;
        $form=$this->createFormBuilder($post)
            ->add('title',TextType::class , array('attr'=>array('class'=>'form-control')))
            ->add('description',TextareaType::class , array('attr'=>array('class'=>'form-control')))
            ->add('save',SubmitType::class , array('label'=>'Create Post','attr'=>array('class'=>'btn btn-primary','style'=>'margin-top:10px')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $description = $form['description']->getData();

            $post->setTitle($title);
            $post->setDescription($description);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('message', 'Poost Saved Successfully!');

            return $this->redirectToRoute('viewpage');
        }
        // replace this example code with whatever you need
        return $this->render('Pages/create.html.twig',['form'=>$form->createView()]);

    }


    /**
     * @Route("/post/edit/{id}", name="editpage")
     */
    public function editAction($id,Request $request)
    {

        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);

        $post->setTitle($post->getTitle());
        $post->setDescription($post->getDescription());

        $form=$this->createFormBuilder($post)
            ->add('title',TextType::class , array('attr'=>array('class'=>'form-control')))
            ->add('description',TextareaType::class , array('attr'=>array('class'=>'form-control')))
            ->add('save',SubmitType::class , array('label'=>'Create Post','attr'=>array('class'=>'btn btn-primary','style'=>'margin-top:10px')))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $description = $form['description']->getData();

            $em = $this->getDoctrine()->getManager();

            $post = $em->getRepository('AppBundle:Post')->find($id);

            $post->setTitle($title);
            $post->setDescription($description);


            $em->flush();
            $this->addFlash('message', 'Post Saved Successfully!');
            return $this->redirectToRoute('viewpage');
        }

//        echo $id;
//        exit();
        // replace this example code with whatever you need
        return $this->render('Pages/edit.html.twig',['form'=>$form->createView()]);
    }


    /**
     * @Route("/post/delete/{id}", name="deletepage")
     */
    public function deleteAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);
    $em->remove($post);

        $em->flush();
        $this->addFlash('message', 'Post deleted Successfully!');
        return $this->redirectToRoute('viewpage');
    }

    /**
     * @Route("/post/show/{id}", name="showpage")
     */
    public function showAction($id)
    {

        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
     /*  echo'<pre>';
       print_r($posts);
        echo'</pre>';
        exit();*/
        // echo $id;

        // replace this example code with whatever you need
        return $this->render('Pages/view.html.twig',['post'=>$post]);
    }

}
