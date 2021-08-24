<?php

namespace Dfv\DoctrineProtoExtractor\Extractor;


use Doctrine\Common\Annotations\DocParser;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class PropertyVisitor extends NodeVisitorAbstract
{
    private $entities = [];

    private $groupName;

    /**
     * @var string
     */
    private $currentClass;

    /**
     * @var string
     */
    private $currentPropertyName;

    private $serializer;

    public function __construct()
    {
        $this->serializer = new CamelCaseToSnakeCaseNameConverter();
    }


    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            $this->currentClass = $node->name->name;
            $this->entities[$this->currentClass] = [];
        }
        if ($node instanceof Node\Stmt\Property) {
            foreach($node->props as $prop) {
                $this->currentPropertyName = $prop->name->name;
            }

            if ($node->getDocComment()) {
                $annotations = $this->parse($node->getDocComment()->getText());

                $this->entities[$this->currentClass][$this->currentPropertyName] = $annotations;
            }
        }
    }

    /**
     * Parse Doc block
     * @param string $input
     * @return array
     * @throws \ReflectionException
     */
    protected function parse(string $input): array
    {
        $input = strtr($input, [
            '@ORM' => '@Doctrine\ORM\Mapping'
        ]);

        $reflection = new \ReflectionClass(Groups::class);

        // Parse @Groups annotation, cause they contain deserialization group name
        $parser = new DocParser();
        $parser->addNamespace($reflection->getNamespaceName());
        $parser->setIgnoreNotImportedAnnotations(true);

        $annotations = [];
        try {
            $annotations = $parser->parse($input);
        } catch (\Doctrine\Common\Annotations\AnnotationException $e) {
        }

        $groupName = strtr($this->groupName, [
            '{className}' => $this->serializer->normalize($this->currentClass)
        ]);

        $applicable = false;
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Groups) {
                if (in_array($groupName, $annotation->getGroups(), true)) {
                    $applicable = true;
                    break;
                }
            }
        }

        // If serialization group was not found, skip this field
        if (!$applicable) {
            return [];
        }

        // Parse Doctrine annotation and ignore other
        // We don't use addNamespace, cause it needing enumerate all namespaces of Doctrine
        $parser = new DocParser();
        $parser->setIgnoreNotImportedAnnotations(true);

        try {
            $annotations = array_merge($annotations, $parser->parse($input));
        } catch (\Doctrine\Common\Annotations\AnnotationException $e) {
        } finally {
            return $annotations;
        }
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     */
    public function setEntities(array $entities): void
    {
        $this->entities = $entities;
    }

    /**
     * @param mixed $groupName
     */
    public function setGroupName($groupName): void
    {
        $this->groupName = $groupName;
    }
}
