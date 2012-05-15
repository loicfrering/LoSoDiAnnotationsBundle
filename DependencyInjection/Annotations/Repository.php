<?php

namespace Loso\Bundle\DiAnnotationsBundle\DependencyInjection\Annotations;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
final class Repository extends Annotation
{
    public $entity;
    public $entityManager;
    public $name;
}
