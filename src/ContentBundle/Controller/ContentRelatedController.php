<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\Content;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContentBundle\Entity\ContentRelated;
use ContentBundle\Form\ContentRelatedType;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * ContentRelated controller.
 *
 * @Rest\NamePrefix("content_related_")
 */
class ContentRelatedController extends Controller
{
    /**
     * Array of ContentRelated entities.
     *
     * @Rest\Get("/{_format}", defaults = { "_format" = "json" }
     * )
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/content/relatd/",
     *  description="Returns contentRelated for specified Content",
     *
     *  output={
     *   "class"="ContentBundle\Entity\ContentRelated",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @param Content $content
     * @return View
     */
    public function indexAction()
    {
            $em = $this->getDoctrine()->getManager();
            $contentRelated = $em->getRepository('ContentBundle:ContentRelated')->findAll();
            $view = View::create()
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:contentRelated:show.html.twig")
                ->setTemplateVar('contentRelated')
                ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
                ->setData($contentRelated);

            return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Creates a new ContentRelated entity.
     *
     * @Rest\Post(
     *     "new/{_format}",
     *     defaults = { "_format" = "json" }
     * )
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     *
     * @ApiDoc(
     *  resource="/api/content/related",
     *  description="Creates new related content",
     *
     *  input={
     *     "class"="ContentBundle\Form\ContentRelatedType"
     *  },
     *
     *  output={
     *   "class"="ContentBundle\Entity\ContentRelated",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @return View
     */
    public function newAction(Request $request, Content $content)
    {

        if(!$content) {
            throw $this->createNotFoundException();
        }
        $contentRelated = new ContentRelated();
        $contentRelated->setContent($content);

        $form = $this->createForm('ContentBundle\Form\ContentRelatedType', $contentRelated);
        $form->submit($request->request->get($form->getName()));

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRelated);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:contentRelated:show.html.twig")
                ->setTemplateVar('contents')
                ->setData($contentRelated);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                ->setTemplateVar('error')
                ->setData($form)
                ->setTemplateData(['message' => $form->getErrors(true)])
                ->setTemplate('ContentBundle:Default:new.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Finds and displays a ContentRelated entity.
     *
     * @Rest\Get(
     *     "/{contentRelated}.{_format}",
     *     requirements={"contentRelated" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Content $content
     * @return View
     * @throws \NotFoundHttpException*
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Returns content data",
     *
     *  output={
     *   "class"="EntriesBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function showAction(ContentRelated $contentRelated)
    {
        if ($contentRelated) {
            $view = View::create()
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:contentRelated:show.html.twig")
                ->setTemplateVar('contentRelated')
                ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
                ->setData([$contentRelated]);

            return $this->get('fos_rest.view_handler')->handle($view);
        }


        throw new \NotFoundHttpException();
    }

    /**
     * Edit an existing ContentRelated entity.
     *
     * @Rest\Patch(
     *     "/{contentRelated}.{_format}",
     *     requirements={"contentRelated" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Content $content
     * @return View
     * @throws \NotFoundHttpException*
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Updates content data",
     *
     *  input="ContentBundle\Form\ContentRelatedType",
     *
     *  output={
     *   "class"="EntriesBundle\Entity\ContentRelated",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function editAction(Request $request, ContentRelated $contentRelated)
    {
        // TODO: fix PATCH
        if (!$contentRelated) {
            throw $this->createNotFoundException();
        }

        $editForm = $this->createForm('ContentBundle\Form\ContentRelatedType', $contentRelated, ['method' => 'PATCH']);
        $editForm->submit($request->request->get($editForm->getName()));

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRelated);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:contentRelated:show.html.twig")
                ->setTemplateVar('contents')
                ->setData($contentRelated);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                ->setTemplateVar('error')
                ->setData($editForm)
                ->setTemplateData(['message' => $editForm->getErrors(true)])
                ->setTemplate('ContentBundle:content:edit.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Deletes a ContentRelated entity.
     *
     * @Rest\Delete(
     *     "/{contentRelated}.{_format}",
     *     requirements={"contentRelated" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Request $request
     * @param ContentRelated $contentRelated
     *
     * @return View
     * @internal param Content $content
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Deletes ContentRelated"
     * )
     */
    public function deleteAction(Request $request, ContentRelated $contentRelated)
    {
        if (!$contentRelated) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();
        $form->submit($request->request->get($form->getName()));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contentRelated);
            $em->flush();

            return View::create()
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:index.html.twig")
                ->setTemplateVar('contents')
                ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
                ->setData(['status'=>true]);
        }

        return View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:content:index.html.twig")
            ->setTemplateVar('contents')
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
            ->setData($form);
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
