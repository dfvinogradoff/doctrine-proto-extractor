<?php
declare(strict_types=1);

namespace Dfv\DoctrineProtoExtractor\Schema\PlantUML;

use Dfv\DoctrineProtoExtractor\Schema\StringerInterface;

class Entity extends \Dfv\DoctrineProtoExtractor\Schema\StringRenderer
{
    /**
     * @var array<Field>
     */
    private $fields = [];

    /**
     * @var string
     */
    private $name;

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/PlantUMLWriter/entity.tpl');
        return \strtr($contents, [
            '{fields}' => $this->stringify($this->getFields()),
            '{name}' => $this->getName(),
        ]);
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function setName(string $name): void
    {
        $parts = explode("\\", $name);
        $className = array_pop($parts);

        $this->name = $className;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field): void
    {
        $this->fields[$field->getName()] = $field;
    }
}