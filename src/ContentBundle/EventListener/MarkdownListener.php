<?php
/**
 * Created by PhpStorm.
 * User: Lukasz Malicki
 * Date: 17.04.16
 * Time: 13:00
 */

namespace ContentBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ContentBundle\Entity\Content;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MarkdownListener
{

    private $_container;

    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if ($entity instanceof Content) {

            $cm = $this->_container->get('core.manager');
            $text = $cm->parseText($entity->getDescription());
            $entity->setDescription($text);
        }
    }
}