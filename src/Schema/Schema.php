<?php


namespace Dfv\DoctrineProtoExtractor\Schema;


class Schema
{
    /** @var array<Entity> */
    private $entities;

    /**
     * @return array<Entity>
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param mixed $entities
     */
    public function setEntities($entities): void
    {
        $this->entities = $entities;
    }
}
