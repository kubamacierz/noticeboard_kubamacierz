<?php

namespace AppBundle\Controller;
//namespace AppBundle\Services;

use AppBundle\Entity\Notice;
use AppBundle\Repository\NoticeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ContextListener;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        if($user AND in_array(strtoupper('ROLE_ADMIN'), $user->getRoles(), true) === true){
            $notices = $repo->findAll();
        } else {
            /** @var NoticeRepository $repo */
            $notices = $repo->getActualNotices();
        }



        return $this->render('notice/index.html.twig', array(
            'notices' => $notices,
        ));
    }

    /**
     * @Route("/showall", name="showallnotices")
     */
    public function showAllNoticesAction(UserInterface $user)
    {
        $em = $this->getDoctrine()->getManager();

        $notices = $em->getRepository('AppBundle:Notice')->findAll();

        $userId = $user->getId();

        return $this->render('AppBundle:LayoutController:show_all_notices.html.twig', array(
            'notices' => $notices,
            'id' => $userId,
        ));
    }

    /**
     * Creates a new notice entity.
     *
     * @Route("/new", name="notice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, UserInterface $user)
    {
        $notice = new Notice();
        $notice->setUser($user);
        $form = $this->createForm('AppBundle\Form\NoticeType', $notice, ['user'=> $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            if($image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
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

            return $this->redirectToRoute('notice_show', array('id' => $notice->getId()));
        }

        return $this->render('notice/new.html.twig', array(
            'notice' => $notice,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a notice entity.
     *
     * @Route("/{id}", name="notice_show")
     * @Method("GET")
     */
    public function showAction(Notice $notice)
    {
        $deleteForm = $this->createDeleteForm($notice);


        return $this->render('notice/show.html.twig', array(
            'notice' => $notice,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing notice entity.
     *
     * @Route("/{id}/edit", name="notice_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Notice $notice)
    {
        $deleteForm = $this->createDeleteForm($notice);
        $editForm = $this->createForm('AppBundle\Form\NoticeType', $notice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notice_edit', array('id' => $notice->getId()));
        }

        return $this->render('notice/edit.html.twig', array(
            'notice' => $notice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a notice entity.
     *
     * @Route("/{id}", name="notice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Notice $notice)
    {
        $form = $this->createDeleteForm($notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notice);
            $em->flush();
        }

        return $this->redirectToRoute('notice_index');
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
            ->getForm()
        ;
    }

    /**
     * @Route("/show/{id}")
     */
    public function showNoticesByUserIdAction($id)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($userId);
        $notices = $user->getNotices();

        return $this->render(':notice:index.html.twig', ['notices' => $notices]);
    }
}
