<?php

namespace ChannelBundle\Controller;

use ChannelBundle\Entity\Channel;
use ChannelBundle\Form\ChannelType;
use Doctrine\ORM\EntityManager;
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
     * @Rest\Get(
     *     "/{_format}",
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     *  description="Returns channels",
     *
     * )
     * @return View
     */
    public function getChannelsAction()
    {
        return View::create()
            ->setStatusCode(200)
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setTemplate("ChannelBundle:Default:list.html.twig")
            ->setData($this->get('doctrine')->getRepository(Channel::class)->findAll());
    }

    /**
     * @Rest\Get("/{channel}", requirements={"channel" = "\d+"})
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Channel $channel
     * @return View
     * @internal param Channel $channel
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
    public function getChannelAction(Channel $channel)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData($channel);
    }

    /**
     * Get a Form instance.
     *
     * @param Channel|null $channel
     * @param string|null  $routeName

     * @return Form
     */
    protected function getForm($channel = null, $routeName = null)
    {
        $options = array();
        if (null !== $routeName) {
            $options['action'] = $this->generateUrl($routeName);
        }
        if (null === $channel) {
            $channel = new Channel(null, null);
        }
        return $this->createForm(ChannelType::class, $channel, $options);
    }

    /**
     * Create a new resource.
     * @Rest\Post("/new")
     * @Rest\View(serializerGroups={"list"})
     * @param Request $request
     *
     * @return View view instance
     *
     * @ApiDoc(
     *  resource="/api/channels/",
     * )
     */
    public function postChannelAction(Request $request)
    {
        $form = $this->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $view = View::create($form);
            $view->setTemplate('ChannelBundle:Default:created.html.twig');
        } else {
            $view = View::create($form);
            $view->setTemplate('ChannelBundle:Default:post.html.twig');
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @Rest\Get("/new")
     * @Rest\View(serializerGroups={"list"})
     *
     * @ApiDoc(
     *  description="Display a new channel form",
     *  resource="/api/channels/",
     * )
     */
    public function newChannelAction(Request $request)
    {
        $data = $this->getForm(null, 'channels_new');
        $view = new View($data);
        $view->setTemplate('ChannelBundle:Default:new.html.twig');
        return $this->get('fos_rest.view_handler')->handle($view);
    }


    /**
     * @Rest\Put("/{channel}", requirements={"channel" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param Channel $channel
     * @return View
     */
    public function updateChannelAction(Channel $channel)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData($channel);
    }

    /**
     * @Rest\Delete("/{channel}", requirements={"channel" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param Channel $channel
     * @return View
     */
    public function deleteChannelAction(Channel $channel)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData([1]);
    }
}
