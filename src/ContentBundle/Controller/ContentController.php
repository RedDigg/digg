<?php

namespace ContentBundle\Controller;

use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContentBundle\Entity\Content;
use ContentBundle\Form\ContentType;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Rest\NamePrefix("content_")
 */
class ContentController extends Controller
{
    /**
     * Array of content entities.
     *
     * @Rest\Get("/.{_format}", defaults = { "_format" = "json" })
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Returns contents",
     *
     *  filters={
     *      {"name"="page", "dataType"="integer", "default"="1"},
     *      {"name"="limit", "dataType"="integer", "default"="50"},
     *      {"name"="type", "dataType"="string", "default"="newest", "options" ="newest|hot"},
     *      {
     *          "name"="channels",
     *          "dataType"="string",
     *          "default"="",
     *          "description"="Channel IDs or names after commas, ex: 'nsfw,geeks,movies,100,150'. Leave empty for all channels."
     *      },
     *  },
     *
     *  output={
     *   "class"="ContentBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @param Request $request
     * @return View
     */
    public function indexAction(Request $request)
    {

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 50);
        $channels = $request->query->get('channels', null);
        $type = $request->query->get('type', 'newest');

        if ($channels) {
            $channels = explode(',', preg_replace('/\s+/', '', $channels));
        }

        if (!in_array($type, ['newest', 'hot'])) {
            throw new BadRequestHttpException(sprintf("Parameter '%s' is not valid.", $type));
        }

        $em = $this->getDoctrine()->getEntityManager();

        //show deleted content for the moderator
        if($this->get('security.authorization_checker')->isGranted('ROLE_MODERATOR')) {
            $em->getFilters()->disable('softdeleteable');
        }

        switch ($type) {
            case 'newest':
                $contents = $em->getRepository('ContentBundle:Content')->getNewestContents($page, $limit, $channels);
                break;
            case 'hot':
                $contents = $em->getRepository('ContentBundle:Content')->findAll();
                break;
        }

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();
        $serializationContext = SerializationContext::create()
            ->setGroups($groups)
            ->enableMaxDepthChecks();

        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:content:index.html.twig")
            ->setTemplateVar('contents')
            ->setSerializationContext($serializationContext)
            ->setData($contents);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Creates a new Content entity.
     *
     * @Rest\Post("/new/.{_format}", defaults = { "_format" = "json" })
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Creates new content",
     *
     *  input={
     *     "class"="ContentBundle\Form\ContentType",
     *     "name" = ""
     *  },
     *
     *  output={
     *   "class"="ContentBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @param Request $request
     * @return View
     */
    public function newAction(Request $request)
    {
        $content = new Content();
        $form = $this->createForm('ContentBundle\Form\ContentType', $content);
        $form->submit($request->request->all());

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups($groups));

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
                ->setData($this->get('validator')->validate($content))
                ->setTemplateData(['message' => $form->getErrors(true)])
                ->setTemplate('ContentBundle:content:show.html.twig');
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
        $em = $this->getDoctrine()->getEntityManager();
        $em->getFilters()->disable('softdeleteable');

        if(!$this->get('security.authorization_checker')->isGranted('ROLE_MODERATOR')) {
            if(!$content) {
                $this->createNotFoundException();
            }
        }

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:content:show.html.twig")
            ->setTemplateVar('content')
            ->setSerializationContext(SerializationContext::create()->setGroups($groups))
            ->setData($content);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Edit an existing Content entity.
     *
     * @Rest\Patch(
     *     "/{content}.{_format}",
     *     requirements={"content" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Request $request
     * @param Content $content
     * @return View
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Updates content data",
     *
     *  input={
     *     "class"="ContentBundle\Form\ContentType",
     *     "name" = ""
     *  },
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
        $editForm = $this->createForm('ContentBundle\Form\ContentType', $content);
        $editForm->submit($request->request->all(), false);

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();
        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups($groups));

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
                ->setData($this->get('validator')->validate($content))
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
     *  description="Deletes content"
     * )
     */
    public function deleteAction(Request $request, Content $content)
    {
        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();
        $form->submit($request->request->get($form->getName()));

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();
        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups($groups));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($content);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:index.html.twig")
                ->setTemplateVar('contents')
                ->setData(['status' => true]);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:index.html.twig")
                ->setTemplateVar('contents')
                ->setData($this->get('validator')->validate($content));
        }

        return $this->get('fos_rest.view_handler')->handle($view);


    }

}
