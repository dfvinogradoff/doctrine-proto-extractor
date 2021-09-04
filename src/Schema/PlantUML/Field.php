<?php

namespace Dfv\DoctrineProtoExtractor\Schema\PlantUML;

use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;

class Field extends StringRenderer
{
    /** @var string */
    private $name;

    /** @var Entity */
    private $kind;

    /** @var string */
    private $type;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Entity
     */
    public function getKind(): Entity
    {
        return $this->kind;
    }

    /**
     * @param Entity $kind
     */
    public function setKind(Entity $kind): void
    {
        $this->kind = $kind;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/PlantUMLWriter/field.tpl');
        return \strtr($contents, [
            '{name}' => $this->getName(),
            '{kind}' => $this->getType() ?? ($this->getKind() ? $this->getKind()->getName() : 'Any'),
        ]);
    }

}