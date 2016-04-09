<?php

namespace ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContentBundle\Entity\ContentRelated;
use ContentBundle\Form\ContentRelatedType;

/**
 * ContentRelated controller.
 *
 * @Route("/content/related")
 */
class ContentRelatedController extends Controller
{
    /**
     * Lists all ContentRelated entities.
     *
     * @Route("/", name="content_related_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contentRelateds = $em->getRepository('ContentBundle:ContentRelated')->findAll();

        return $this->render('contentrelated/index.html.twig', array(
            'contentRelateds' => $contentRelateds,
        ));
    }

    /**
     * Creates a new ContentRelated entity.
     *
     * @Route("/new", name="content_related_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contentRelated = new ContentRelated();
        $form = $this->createForm('ContentBundle\Form\ContentRelatedType', $contentRelated);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRelated);
            $em->flush();

            return $this->redirectToRoute('content_related_show', array('id' => $contentRelated->getId()));
        }

        return $this->render('contentrelated/new.html.twig', array(
            'contentRelated' => $contentRelated,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ContentRelated entity.
     *
     * @Route("/{id}", name="content_related_show")
     * @Method("GET")
     */
    public function showAction(ContentRelated $contentRelated)
    {
        $deleteForm = $this->createDeleteForm($contentRelated);

        return $this->render('contentrelated/show.html.twig', array(
            'contentRelated' => $contentRelated,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ContentRelated entity.
     *
     * @Route("/{id}/edit", name="content_related_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContentRelated $contentRelated)
    {
        $deleteForm = $this->createDeleteForm($contentRelated);
        $editForm = $this->createForm('ContentBundle\Form\ContentRelatedType', $contentRelated);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRelated);
            $em->flush();

            return $this->redirectToRoute('content_related_edit', array('id' => $contentRelated->getId()));
        }

        return $this->render('contentrelated/edit.html.twig', array(
            'contentRelated' => $contentRelated,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ContentRelated entity.
     *
     * @Route("/{id}", name="content_related_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContentRelated $contentRelated)
    {
        $form = $this->createDeleteForm($contentRelated);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contentRelated);
            $em->flush();
        }

        return $this->redirectToRoute('content_related_index');
    }

    /**
     * Creates a form to delete a ContentRelated entity.
     *
     * @param ContentRelated $contentRelated The ContentRelated entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContentRelated $contentRelated)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('content_related_delete', array('id' => $contentRelated->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
