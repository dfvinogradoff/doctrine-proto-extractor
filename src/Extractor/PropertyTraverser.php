<?php

namespace Dfv\DoctrineProtoExtractor\Extractor;


use Doctrine\Common\Annotations\DocParser;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Symfony\Component\Serializer\Annotation\Groups;

class PropertyTraverser extends NodeVisitorAbstract
{
    private $entities = [];

    /**
     * @var string
     */
    private $currentClass;

    /**
     * @var string
     */
    private $currentPropertyName;


    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            $this->currentClass = $node->name->name;
            $this->entities[$this->currentClass] = [];
        }
        if ($node instanceof Node\Stmt\Property) {
            if ($node->getDocComment()) {
                $annotations = $this->parse($node->getDocComment()->getText());

                $this->entities[$this->currentClass][$this->currentPropertyName] = $annotations;
            }
        }
        if ($node instanceof Node\Stmt\PropertyProperty) {
            $this->currentPropertyName = $node->name->name;
            $this->entities[$this->currentClass][$this->currentPropertyName] = [];
        }
    }

    /**
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

        // Parse @Groups annotation, cause they contains deserialization group name
        $parser = new DocParser();
        $parser->addNamespace($reflection->getNamespaceName());
        $parser->setIgnoreNotImportedAnnotations(true);

        $annotations = [];
        try {
            $annotations = $parser->parse($input);
        } catch (\Doctrine\Common\Annotations\AnnotationException $e) {
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
}
