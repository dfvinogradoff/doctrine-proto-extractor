<?php


namespace Dfv\DoctrineProtoExtractor\Writer;


use Dfv\DoctrineProtoExtractor\Extractor\PropertyVisitor;
use Dfv\DoctrineProtoExtractor\Schema\Cardinality\OptionalCardinality;
use Dfv\DoctrineProtoExtractor\Schema\Cardinality\RepeatedCardinality;
use Dfv\DoctrineProtoExtractor\Schema\Field;
use Dfv\DoctrineProtoExtractor\Schema\FieldFactory;
use Dfv\DoctrineProtoExtractor\Schema\Message;
use Dfv\DoctrineProtoExtractor\Schema\Method;
use Dfv\DoctrineProtoExtractor\Schema\Proto;
use Dfv\DoctrineProtoExtractor\Schema\Types\MessageType;
use Dfv\DoctrineProtoExtractor\Schema\Types\StringType;

class Proto3Writer implements WriterInterface
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

    /**
     * @return mixed|void
     */
    public function write(): string
    {

        $proto = new Proto();
        $proto->setPackage($this->package);
        $proto->setProtoVersion($this->protoVersion);
        $proto->setService($this->service);
        $proto->setGoPackage($this->goPackage);

        $methods = [];
        foreach ($this->propertyTraverser->getEntities() as $className => $properties) {
            $methods[] = new Method($className);
            $message = new Message();
            $message->setName($className);
            $number = 1;
            foreach ($properties as $name => $annotations) {
                $field = new Field();
                $field->setName($name);
                $field->setNumber($number);
                $field->setKind(new StringType());
                $number++;

                foreach ($annotations as $annotation) {
                    if ($annotation instanceof \Doctrine\ORM\Mapping\ManyToOne) {
                        $field->setKind(new MessageType($annotation->targetEntity));
                        break;
                    }
                    if ($annotation instanceof \Doctrine\ORM\Mapping\ManyToMany) {
                        $field->setCardinality(new RepeatedCardinality());
                        $field->setKind(new MessageType($annotation->targetEntity));
                        break;
                    }
                    if ($annotation instanceof \Doctrine\ORM\Mapping\OneToMany) {
                        $field->setCardinality(new RepeatedCardinality());
                        $field->setKind(new MessageType($annotation->targetEntity));
                        break;
                    }
                    if ($annotation instanceof \Doctrine\ORM\Mapping\OneToOne) {
                        $field->setKind(new MessageType($annotation->targetEntity));
                        break;
                    }
                }
                $message->addField($field);
            }

            $proto->addMessage($message);
        }

        $proto->setMethods($methods);

        return $proto->render();
    }

    public function supports($syntax): bool
    {
        return "proto3" === $syntax;
    }
}