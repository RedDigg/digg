<?php

namespace ContentBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContentBundle\Entity\Content;
use FOS\RestBundle\Controller\Annotations as Rest;
use ContentBundle\Form\ContentType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Rest\NamePrefix("content_")
 */
class ContentController extends Controller
{
    /**
     * Array of content entities.
     *
     * @Rest\Get("/")
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Returns contents",
     *
     *  output={
     *   "class"="ContentBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @return View
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $contents = $em->getRepository('ContentBundle:Content')->findAll();

        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:content:index.html.twig")
            ->setTemplateVar('contents')
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
            ->setData($contents);

        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Creates a new Content entity.
     *
     * @Rest\Post("/new")
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Returns contents",
     *
     *  input="ContentBundle\Form\ContentType",
     *
     *  output={
     *   "class"="ContentBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @return View
     */
    public function newAction(Request $request)
    {
        $content = new Content();
        $form = $this->createForm('ContentBundle\Form\ContentType', $content);
        $form->submit($request->request->get($form->getName()));

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($content);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:show.html.twig")
                ->setTemplateVar('contents')
                ->setData($content);

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
     * @Rest\Get(
     *     "/{content}.{_format}",
     *     requirements={"content" = "\d+"},
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
    public function showAction(Content $content)
    {
        if ($content) {
            $view = View::create()
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:show.html.twig")
                ->setTemplateVar('content')
                ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
                ->setData($content);

            return $this->get('fos_rest.view_handler')->handle($view);
        }


        throw new \NotFoundHttpException();
    }

    /**
     * Edit an existing Content entity.
     *
     * @Rest\Put(
     *     "/{content}.{_format}",
     *     requirements={"content" = "\d+"},
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
     *  input="ContentBundle\Form\ContentType",
     *
     *  output={
     *   "class"="EntriesBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function editAction(Request $request, Content $content)
    {
        if (!$content) {
            throw $this->createNotFoundException();
        }

        $editForm = $this->createForm('ContentBundle\Form\ContentType', $content);
        $editForm->submit($request->request->get($editForm->getName()));

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($content);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:show.html.twig")
                ->setTemplateVar('contents')
                ->setData($content);

        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                ->setTemplateVar('error')
                ->setData($editForm)
                ->setTemplateData(['message' => $editForm->getErrors(true)])
                ->setTemplate('ContentBundle:content:show.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Delete an existing Content entity.
     *
     * @Rest\Delete(
     *     "/{content}.{_format}",
     *     requirements={"content" = "\d+"},
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
     *  input="ContentBundle\Form\ContentType",
     *
     *  output={
     *   "class"="EntriesBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function deleteAction(Request $request, Content $content)
    {
        if (!$content) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();
        $form->submit($request->request->get($form->getName()));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($content);
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

}
