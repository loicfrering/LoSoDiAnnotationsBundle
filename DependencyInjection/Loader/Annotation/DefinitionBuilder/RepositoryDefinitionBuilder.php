<?php

namespace LoSo\LosoBundle\DependencyInjection\Loader\Annotation\DefinitionBuilder;

use LoSo\LosoBundle\DependencyInjection\Loader\DoctrineServicesUtils;
use LoSo\LosoBundle\DependencyInjection\Loader\Annotation\ServiceIdGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Loïc Frering <loic.frering@gmail.com>
 */
class RepositoryDefinitionBuilder extends AbstractServiceDefinitionBuilder
{
    public function build(\ReflectionClass $reflClass, $annot)
    {
        $definitionHolder = parent::build($reflClass, $annot);
        $definition = $definitionHolder['definition'];
        $id = $annot->name;
        if (empty($id)) {
            $serviceIdGenerator = new ServiceIdGenerator();
            $id = $serviceIdGenerator->generate($reflClass);
        }

        $doctrineServicesUtils = new DoctrineServicesUtils();

        $entity = $annot->value ?: $annot->entity;
        $entityManager = !empty($annot->entityManager) ? $annot->entityManager : 'default';
        if (null === $entity) {
            throw new \InvalidArgumentException(sprintf('Entity name must be setted in @Repository for class "%s".', $reflClass->getName()));
        }

        $entityManagerRef = $doctrineServicesUtils->getEntityManagerReference($entityManager);
        $entityMetadataRef = $doctrineServicesUtils->getEntityMetadataReference($entity, $entityManager);
        $definition->setArguments(array($entityManagerRef, $entityMetadataRef));
        $definition->addTag('loso.doctrine.repository', array(
            'entity' => $entity,
            'entityManager' => $entityManager
        ));

        return array('id' => $id, 'definition' => $definition);
    }
}
