<?php

namespace Dfv\DoctrineProtoExtractor\Schema\PlantUML;

use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;

class UML extends StringRenderer
{
    /** @var array<Entity> */
    private $entities = [];

    /** @var array<Relation> */
    private $relations = [];

    /**
     * @return Entity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param Entity[] $entities
     */
    public function setEntities(array $entities): void
    {
        $this->entities = $entities;
    }

    /**
     * @param Entity $entity
     */
    public function addEntity(Entity $entity): void
    {
        $this->entities[$entity->getName()] = $entity;
    }

    /**
     * @return Relation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param Relation[] $relations
     */
    public function setRelations(array $relations): void
    {
        $this->relations = $relations;
    }

    /**
     * @param Relation|null $relation
     */
    public function addRelation(?Relation $relation): void
    {
        if ($relation instanceof Relation) {
            $relations = [$relation->getFrom(), $relation->getTo()];
            sort($relations);
            $this->relations[implode("<>", $relations)] = $relation;
        }
    }

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/PlantUMLWriter/uml.tpl');
        return \strtr($contents, [
            '{entities}' => $this->stringify($this->getEntities()),
            '{relations}' => $this->stringify($this->getRelations())
        ]);
    }

}