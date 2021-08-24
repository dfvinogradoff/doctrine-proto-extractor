<?php


namespace Dfv\DoctrineProtoExtractor\Schema\Proto3;


use Dfv\DoctrineProtoExtractor\Schema\Proto3\Field;
use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;
use Dfv\DoctrineProtoExtractor\Schema\Proto3\Types\CommonType;
use function Dfv\DoctrineProtoExtractor\Schema\strtr;

class Message extends StringRenderer
{
    /**
     * @var
     */
    private $name;

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array<\Dfv\DoctrineProtoExtractor\Schema\Proto3\Types\CommonType> $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/Proto3Writer/message.tpl');
        return \strtr($contents, [
            '{message}' => $this->getName(),
            '{fields}' => $this->stringify($this->getFields())
        ]);
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }
}
