<?php

namespace Dfv\DoctrineProtoExtractor\Schema\PlantUML;

use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Association representation of classes
 *
 * Example, Class1 "1" -- "1" Class2, that means Class1 has one-to-one association with Class2
 */
class Association extends StringRenderer
{
    /** @var  */
    private $type;

    /**
     * @param int $type
     */
    public function __construct(int $type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function render(): string
    {
        switch ($this->type) {
            case ClassMetadataInfo::ONE_TO_ONE: return '"1" -- "1"';
            case ClassMetadataInfo::ONE_TO_MANY: return '"1" -- "N"';
            case ClassMetadataInfo::MANY_TO_MANY: return '"N" -- "N"';
            case ClassMetadataInfo::MANY_TO_ONE: return '"N" -- "1"';
        }

        return ' -- ';
    }
}