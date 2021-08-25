<?php

namespace Dfv\DoctrineProtoExtractor\Writer\PlantUMLWriter;

use Dfv\DoctrineProtoExtractor\Extractor\PropertyVisitor;
use Dfv\DoctrineProtoExtractor\Schema\PlantUML\Association;
use Dfv\DoctrineProtoExtractor\Schema\PlantUML\Entity;
use Dfv\DoctrineProtoExtractor\Schema\PlantUML\Field;
use Dfv\DoctrineProtoExtractor\Schema\PlantUML\Relation;
use Dfv\DoctrineProtoExtractor\Schema\PlantUML\UML;
use Dfv\DoctrineProtoExtractor\Writer\WriterInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class PlantUMLWriter implements WriterInterface
{

    /**
     * @var PropertyVisitor
     */
    private $propertyTraverser;
    private $package;
    private $protoVersion;
    private $service;
    private $goPackage;

    public function __construct(PropertyVisitor $propertyTraverser, $service, $goPackage, $package, $protoVersion)
    {
        $this->propertyTraverser = $propertyTraverser;
        $this->package = $package;
        $this->protoVersion = $protoVersion;
        $this->service = $service;
        $this->goPackage = $goPackage;
    }

    public function write(): string
    {
        $uml = new UML();

        foreach ($this->propertyTraverser->getEntities() as $className => $properties) {
            $entity = new Entity();
            $entity->setName($className);

            foreach ($properties as $name => $annotations) {
                $field = new Field();
                $field->setName($name);

                $relation = null;
                foreach ($annotations as $annotation) {
                    if ($annotation instanceof \Doctrine\ORM\Mapping\OneToOne) {
                        $to = new Entity();
                        $to->setName($annotation->targetEntity);
                        $field->setKind($to);
                        $relation = new Relation();
                        $relation->setFrom($entity);
                        $relation->setTo($to);
                        $relation->setField($field);
                        $relation->setAssociation(new Association(ClassMetadataInfo::ONE_TO_ONE));
                    }
                    if ($annotation instanceof \Doctrine\ORM\Mapping\OneToMany) {
                        $to = new Entity();
                        $to->setName($annotation->targetEntity);
                        $field->setKind($to);
                        $relation = new Relation();
                        $relation->setFrom($entity);
                        $relation->setTo($to);
                        $relation->setField($field);
                        $relation->setAssociation(new Association(ClassMetadataInfo::ONE_TO_MANY));
                    }
                    if ($annotation instanceof \Doctrine\ORM\Mapping\ManyToMany) {
                        $to = new Entity();
                        $to->setName($annotation->targetEntity);
                        $field->setKind($to);
                        $relation = new Relation();
                        $relation->setFrom($entity);
                        $relation->setTo($to);
                        $relation->setField($field);
                        $relation->setAssociation(new Association(ClassMetadataInfo::MANY_TO_MANY));
                    }
                    if ($annotation instanceof \Doctrine\ORM\Mapping\ManyToOne) {
                        $to = new Entity();
                        $to->setName($annotation->targetEntity);
                        $field->setKind($to);
                        $relation = new Relation();
                        $relation->setFrom($entity);
                        $relation->setTo($to);
                        $relation->setField($field);
                        $relation->setAssociation(new Association(ClassMetadataInfo::MANY_TO_ONE));
                    }
                }
                if (!$relation) {
                    $field->setType('Any');
                }
                $uml->addRelation($relation);
                $entity->addField($field);
            }
            $uml->addEntity($entity);
        }

        return $uml->render();
    }

    public function supports($syntax): bool
    {
        return "plantuml" === $syntax;
    }
}