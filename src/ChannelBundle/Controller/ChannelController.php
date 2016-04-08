<?php

namespace EntriesBundle\Controller;

use ChannelBundle\Entity\Channel;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use CoreBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Rest\NamePrefix("channel_")
 */
class ChannelController extends BaseController
{

    /**
     * @Rest\Get("/")
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns channels",
     *
     * )
     * @param EntityManager $em
     * @return View
     */
    public function getChannelsAction(EntityManager $em)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData($em->getRepository(Channel::class)->findAll());
    }

    /**
     * @Rest\Get("/{channel}", requirements={"channel" = "\d+"})
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Channel $channel
     * @return View
     * @internal param Channel $channel
     * @ApiDoc(
     *  resource=true,
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
     * @Rest\Post("/new")
     * @Rest\View(serializerGroups={"list"})
     *
     * @ApiDoc(
     *  description="Create a new channel",
     *  input="Your\Namespace\Form\Type\YourType",
     *  output={
     *   "class"="ChannelBundle\Entity\Channel",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"list"}
     *  }
     * )
     */
    public function newChannelAction(Request $request)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData(['lala']);
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

    public function testAction()
    {
        return $this->render('ChannelBundle:Default:index.html.twig', []);
    }
}
