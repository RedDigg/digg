<?php

namespace ChannelBundle\Controller;

use ChannelBundle\Entity\Channel;
use ChannelBundle\Form\ChannelType;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use CoreBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Rest\NamePrefix("channels_")
 */
class ChannelController extends BaseController
{


    /**
     * Returns channels list.
     *
     * @Rest\Get("/.{_format}", defaults = { "_format" = "json" })
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     *  description="Returns all channels",
     *
     *  output={
     *   "class"="ChannelBundle\Entity\Channel",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @return View
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contents = $em->getRepository(Channel::class)->findAll();

        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ChannelBundle:Default:index.html.twig")
            ->setTemplateVar('channels')
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
            ->setData($contents);

        return $this->get('fos_rest.view_handler')->handle($view);
    }


    /**
     * @Rest\Get(
     *     "/{channel}.{_format}",
     *     requirements={"channel" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Channel $channel
     * @return View
     * @internal param Channel $channel
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     *  description="Returns channel data",
     *
     *  output={
     *   "class"="ChannelBundle\Entity\Channel",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function showAction(Channel $channel)
    {
        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ChannelBundle:Default:show.html.twig")
            ->setTemplateVar('channel')
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']))
            ->setData($channel);

        return $this->get('fos_rest.view_handler')->handle($view);

    }


    /**
     * Create a new resource.
     * @Rest\Post("/new/.{_format}", defaults = { "_format" = "json" })
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Request $request
     *
     * @return View view instance
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     *  input={
     *     "class"="ChannelBundle\Form\ChannelType",
     *     "name" = ""
     *  },
     *  output={
     *   "class"="ChannelBundle\Entity\Channel",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function newAction(Request $request)
    {
        $channel = new Channel();
        $form = $this->createForm('ChannelBundle\Form\ChannelType', $channel);

        $form->submit($request->request->all());

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();

            $view->setData($channel)
                ->setTemplateVar('channel')
                ->setTemplate('ChannelBundle:Default:show.html.twig');
        } else {
            $view->setTemplateVar('error')
                ->setData($form)
                ->setTemplateData(['message' => $form->getErrors(true)])
                ->setTemplate('ChannelBundle:Default:post.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

//    /**
//     * @Rest\Get("/new")
//     * @Rest\View(serializerGroups={"list"})
//     *
//     * @ApiDoc(
//     *  description="Display a new channel form",
//     *  resource="/api/channels/",
//     * )
//     */
//    public function newChannelAction(Request $request)
//    {
//        $data = $this->getForm(null, 'channels_new');
//        $view = new View($data);
//        $view->setTemplate('ChannelBundle:Default:new.html.twig');
//        return $this->get('fos_rest.view_handler')->handle($view);
//    }


    /**
     * Edit an existing Channel entity.
     *
     * @Rest\Patch(
     *     "/{channel}.{_format}",
     *     requirements={"channel" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @param Channel $channel
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     *  description="Updates channel data",
     *
     *  input={
     *     "class"="ChannelBundle\Form\ChannelType",
     *     "name" = ""
     *  },
     *
     *  output={
     *   "class"="ChannelBundle\Entity\Channel",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @return View
     */
    public function editAction(Request $request, Channel $channel)
    {
        $editForm = $this->createForm('ChannelBundle\Form\ChannelType', $channel);
        $editForm->submit($request->request->all(), false);

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ChannelBundle:Default:show.html.twig")
                ->setTemplateVar('channel')
                ->setData($channel);

        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                ->setTemplateVar('error')
                ->setData($editForm)
                ->setTemplateData(['message' => $editForm->getErrors(true)])
                ->setTemplate('ChannelBundle:Default:show.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Delete an existing Content entity.
     *
     * @Rest\Delete(
     *     "/{channel}.{_format}",
     *     requirements={"channel" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Channel $channel
     * @return View
     * @throws \NotFoundHttpException*
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     *  description="Deletes content"
     * )
     */
    public function deleteAction(Request $request, Channel $channel)
    {
        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();
        $form->submit($request->request->get($form->getName()));

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups(['user']));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($channel);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ChannelBundle:Default:index.html.twig")
                ->setTemplateVar('channels')
                ->setData(['status' => true]);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:index.html.twig")
                ->setTemplateVar('channels')
                ->setData($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);

    }
}
