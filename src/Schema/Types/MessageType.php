<?php

namespace Dfv\DoctrineProtoExtractor\Schema\Types;

class MessageType extends CommonType
{
    /** @var string */
    private $typeOf;

    public function __construct(string $typeOf)
    {
        $parts = explode("\\", $typeOf);
        $className = array_pop($parts);

        $this->typeOf = $className;
    }

    public function __toString(): string
    {
        return $this->typeOf;
    }
}
