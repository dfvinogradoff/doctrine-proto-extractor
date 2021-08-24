<?php

namespace Dfv\DoctrineProtoExtractor\Schema\PlantUML;

use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;

class Relation extends StringRenderer
{
    /** @var Entity */
    private $from;

    /** @var Entity */
    private $to;

    /** @var Association */
    private $association;

    /** @var Field */
    private $field;

    /**
     * @return Entity
     */
    public function getFrom(): Entity
    {
        return $this->from;
    }

    /**
     * @param Entity $from
     */
    public function setFrom(Entity $from): void
    {
        $this->from = $from;
    }

    /**
     * @return Entity
     */
    public function getTo(): Entity
    {
        return $this->to;
    }

    /**
     * @param Entity $to
     */
    public function setTo(Entity $to): void
    {
        $this->to = $to;
    }

    /**
     * @return Association
     */
    public function getAssociation(): Association
    {
        return $this->association;
    }

    /**
     * @param Association $association
     */
    public function setAssociation(Association $association): void
    {
        $this->association = $association;
    }

    /**
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field): void
    {
        $this->field = $field;
    }

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/PlantUMLWriter/relation.tpl');
        return \strtr($contents, [
            '{from}' => $this->getFrom()->getName(),
            '{to}' => $this->getTo()->getName(),
            '{assoc}' => $this->getAssociation()->render(),
            '{field}' => $this->getField()->getName()
        ]);
    }
}