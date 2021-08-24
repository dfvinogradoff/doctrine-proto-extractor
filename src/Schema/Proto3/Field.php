<?php


namespace Dfv\DoctrineProtoExtractor\Schema\Proto3;


use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;
use Dfv\DoctrineProtoExtractor\Schema\Proto3\Types\CommonType;
use function Dfv\DoctrineProtoExtractor\Schema\strtr;

class Field extends StringRenderer
{
    private $name;

    /** @var CommonType */
    private $kind;

    /**
     * @var
     */
    private $cardinality;

    private $number;

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
     * @return CommonType
     */
    public function getKind(): CommonType
    {
        return $this->kind;
    }

    /**
     * @param CommonType $kind
     */
    public function setKind(CommonType $kind): void
    {
        $this->kind = $kind;
    }

    /**
     * @return mixed
     */
    public function getCardinality()
    {
        return $this->cardinality;
    }

    /**
     * @param mixed $cardinality
     */
    public function setCardinality($cardinality): void
    {
        $this->cardinality = $cardinality;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/Proto3Writer/field.tpl');
        return \strtr(trim($contents), [
            '{cardinality}' => (string) $this->getCardinality(),
            '{kind}' => (string) $this->getKind(),
            '{name}' => $this->getName(),
            '{number}' => $this->getNumber()
        ]);
    }
}
