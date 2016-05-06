<?php

namespace EntriesBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use CoreBundle\Controller\BaseController;
use EntriesBundle\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Rest\NamePrefix("entry_")
 */
class DefaultController extends BaseController
{

    private $_userGrantedApiGroups;

    public function __construct()
    {
        $this->_userGrantedApiGroups = $this->get('user_bundle.user')->getGrantedAPIGroups();
    }

    /**
     * @Rest\Get("/")
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/entry/",
     *  description="Returns entries",
     *
     * )
     */
    public function getEntriesAction()
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups($this->_userGrantedApiGroups))
            ->setData([1]);
    }

    /**
     * @Rest\Get(
     *     "/{entry}.{_format}",
     *     requirements={"entry" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Entry $entry
     * @return View
     * @throws \NotFoundHttpException*
     *
     * @ApiDoc(
     *  resource="/api/entry/",
     *  description="Returns entry data",
     *
     *  output={
     *   "class"="EntriesBundle\Entity\EntryComment",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function getEntryAction(Entry $entry)
    {
        if ($entry) {

            $view = View::create()
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("EntriesBundle:Default:entry.html.twig")
                ->setTemplateVar('entry')
                ->setSerializationContext(SerializationContext::create()->setGroups($this->_userGrantedApiGroups))
                ->setData($entry);

            return $this->handleView($view);

        } else {
            throw new \NotFoundHttpException();
        }
    }

    public function getUserIP() {
        if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
                $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }


    /**
     * @Rest\Post("/new")
     * @Rest\View(serializerGroups={"list"})
     *
     * @ApiDoc(
     *  resource="/api/entry/",
     *  description="Create a new entry",
     *  input="Your\Namespace\Form\Type\YourType",
     *  output={
     *   "class"="EntriesBundle\Entity\Entry",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"list"}
     *  }
     * )
     */
    public function newEntryAction(Request $request)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups($this->_userGrantedApiGroups))
            ->setData(['lala']);
    }


    /**
     * @Rest\Put("/{entry}", requirements={"entry" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param Entry $entry
     * @return View
     */
    public function updateEntryAction(Entry $entry)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups($this->_userGrantedApiGroups))
            ->setData($entry);
    }

    /**
     * @Rest\Delete("/{entry}", requirements={"entry" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param Entry $entry
     * @return View
     */
    public function deleteEntryAction(Entry $entry)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups($this->_userGrantedApiGroups))
            ->setData([1]);
    }

    public function testAction()
    {
        return $this->render('EntriesBundle:Default:index.html.twig', []);
    }
}
