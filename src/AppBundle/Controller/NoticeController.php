<?php

namespace AppBundle\Controller;
//namespace AppBundle\Services;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Notice;
use AppBundle\Repository\NoticeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ContextListener;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Session;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Notice controller.
 *
 * @Route("notice")
 */
class NoticeController extends Controller
{
    /**
     * Lists all notice entities.
     *
     * @Route("/", name="notice_index")
     * @Method("GET")
     */
    public function indexAction(?UserInterface $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Notice');
        if ($user AND in_array(strtoupper('ROLE_ADMIN'), $user->getRoles(), true) === true) {
            $notices = $repo->findAll();
        } else {
            /** @var NoticeRepository $repo */
            $notices = $repo->getActualNotices();
        }
        $deleteForms = [];
        foreach ($notices as $notice) {
            $deleteFormView = $this->createDeleteForm($notice)->createView();
            $deleteForms[] = $deleteFormView;
        }

        return $this->render(
//            'notice/index.html.twig',
            'AppBundle:LayoutController:show_notices.html.twig',
            [
            'notices' => $notices,
            'delete_forms' => $deleteForms,
            'tableTitle' => 'All notices'
        ]);
    }

//    /**
//     * @Route("/showall", name="showallnotices")
//     */
//    public function showAllNoticesAction(UserInterface $user)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $notices = $em->getRepository('AppBundle:Notice')->findAll();
//
//        $userId = $user->getId();
//
//        return $this->render('AppBundle:LayoutController:show_all_notices.html.twig', array(
//            'notices' => $notices,
//            'id' => $userId,
//            'username' => $user
//        ));
//    }

    /**
     * Creates a new notice entity.
     *
     * @Route("/new", name="notice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, UserInterface $user)
    {
//        print($request->getPathInfo());
        $notice = new Notice();
        $notice->setUser($user);
        if(! in_array('ROLE_ADMIN',$user->getRoles() )){
            $notice->setExpiration(new DateTime('+7 days'));
        }

        $form = $this->createForm('AppBundle\Form\NoticeType', $notice, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }


                $notice->setImage($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($notice);
            $em->flush();

            return $this->redirectToRoute('notice_show', array(
                'id' => $notice->getId(),
                'username' => $user
            ));
        }

        return $this->render('notice/new.html.twig', array(
            'notice' => $notice,
            'form' => $form->createView(),
            'username' => $user
        ));
    }

    /**
     * Finds and displays a notice entity.
     *
     * @Route("/{id}", name="notice_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Notice $notice, ?UserInterface $user)
    {

        $deleted = $this->deleteActionIfShouldBeDeleted($request, $notice);
        if ($deleted) {
            return $deleted;
        }

        $comment = new Comment();
        $form = $this->createForm('AppBundle\Form\CommentType', $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->setNotice($notice);
            $comment->setUser($user);
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('notice/show.html.twig', array(
            'notice' => $notice,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing notice entity.
     *
     * @Route("/{id}/edit", name="notice_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Notice $notice, UserInterface $user, SessionInterface $session)
    {

//        $notice = new Notice();
//        $notice->setUser($user);
//        $notice->setExpiration($notice->getExpiration());
        $form = $this->createForm('AppBundle\Form\NoticeType', $notice, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }


                $notice->setImage($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($notice);
            $em->flush();

//            $this->addFlash('success', 'Notice Updated! Inaccuracies squashed!');
            $session->getFlashBag()->add('success','Pomyslnie dokonano edycji!');

            return $this->redirectToRoute('notice_edit', array('id' => $notice->getId()));
        }

        return $this->render('notice/edit.html.twig', array(
            'notice' => $notice,
            'form' => $form->createView(),
        ));


//        $deleteForm = $this->createDeleteForm($notice);
//        $editForm = $this->createForm('AppBundle\Form\NoticeType', $notice, ['user' => $this->getUser()]);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('notice_edit', array('id' => $notice->getId()));
//        }
//
//        return $this->render('notice/edit.html.twig', array(
//            'notice' => $notice,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));
    }

    /**
     * Deletes a notice entity.
     *
     * @Route("/{id}", name="notice_delete", methods={"GET","POST"})
     */
    public function deleteActionIfShouldBeDeleted(Request $request, Notice $notice)
    {
        $form = $this->createDeleteForm($notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notice);
            $em->flush();
            return $this->redirectToRoute("menu");
        }

        return false;

    }

    /**
     * Creates a form to delete a notice entity.
     *
     * @param Notice $notice The notice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Notice $notice)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notice_delete', array('id' => $notice->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/show/{id}")
     */
    public function showNoticesByUserIdAction(UserInterface $user)
    {

        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($userId);
//        $notices = $user->getNotices();


        $repo = $em->getRepository('AppBundle:Notice');
        /** @var NoticeRepository $repo */
        $notices = $repo->getActualNoticesById($user);

        $deleteForms = [];
        foreach ($notices as $notice) {
            $deleteFormView = $this->createDeleteForm($notice)->createView();
            $deleteForms[] = $deleteFormView;
        }

        return $this->render(
//            'AppBundle:LayoutController:show_user_notices.html.twig',
            'AppBundle:LayoutController:show_notices.html.twig',
            [
            'notices' => $notices,
            'delete_forms' => $deleteForms,
            'username' => $user,
            'tableTitle' => 'Your notices'
        ]);
    }
}
