<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Repository\CommentRepository;
use AppBundle\Entity\Notice;
use AppBundle\Repository\NoticeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Comment controller.
 *
 * @Route("comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all comment entities.
     *
     * @Route("/", name="comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')->findAll();

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

    /**
     * Creates a new comment entity.
     *
     * @Route("/new", name="comment_new")
     * @Method({"GET", "POST"})
     * @param Notice $notice
     * @param Request $request
     * @param User $user
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Notice $notice, Request $request, UserInterface $user)
    {

        $comment = new Comment();
        $comment->setUser($user);
        $form = $this->createForm('AppBundle\Form\CommentType', $comment, ['user' => $user]);
        $form->handleRequest($request);


//        if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $comment->setNotice($notice);
        $em->persist($comment);
        $em->flush();
//
////            return $this->redirectToRoute('comment_show', array('id' => $comment->getId()));
//        }

        return $this->render('comment/new.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'n' => $notice,
            's' => $form->isSubmitted(),
            'v' => $form->isValid(),
            'r' => $request,
        ));
    }

    /**
     * Finds and displays a comment entity.
     *
     * @Route("/{id}", name="comment_show")
     * @Method("GET")
     */
    public function showAction(Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);

        return $this->render('comment/show.html.twig', array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing comment entity.
     *
     * @Route("/admin/{id}/edit", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);
        $editForm = $this->createForm('AppBundle\Form\CommentType', $comment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_edit', array('id' => $comment->getId()));
        }

        return $this->render('comment/edit.html.twig', array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a comment entity.
     *
     * @Route("/admin/{id}", name="comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->createDeleteForm($comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }
//        $deleteForms = [];
//        foreach ($comments as $comment) {
//            $deleteFormView = $this->createDeleteForm($comment)->createView();
//            $deleteForms[] = $deleteFormView;

        return $this->redirectToRoute('notice_index');

    }

    /**
     * Creates a form to delete a comment entity.
     *
     * @param Comment $comment The comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * @Route("/admin/show/{id}", name="comments_by_notice_id")
     */
    public function showCommentsByNoticeIdAction(Notice $notice)
    {
        $noticeId = $notice->getId();

//        dump($noticeId);
//        die;
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Comment');
        /** @var CommentRepository $repo */
        $comments = $repo->getCommentsByNoticeId($noticeId);

        $deleteForms = [];
        foreach ($comments as $comment) {
            $deleteFormView = $this->createDeleteForm($comment)->createView();
            $deleteForms[] = $deleteFormView;
        }

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
            'delete_forms' => $deleteForms
        ]);

    }

    /**
     * @Route("/admin/show1/{id}", name="comments_by_user_id")
     */
    public function showCommentsByUserId(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $userId = $pathInfo[-1];
//        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository('AppBundle:User')->find($userId);
//        echo $userId;
        $repo = $em->getRepository('AppBundle:Comment');
        /** @var CommentRepository $repo */
        $comments = $repo->getCommentsByUserId($userId);

        $deleteForms = [];
        foreach ($comments as $comment) {
            $deleteFormView = $this->createDeleteForm($comment)->createView();
            $deleteForms[] = $deleteFormView;
        }
            return $this->render('comment/index.html.twig', [
                'comments' => $comments,
                'delete_forms' => $deleteForms
            ]);

    }
}
