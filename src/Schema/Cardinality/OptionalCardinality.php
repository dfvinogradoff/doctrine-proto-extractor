<?php

namespace Dfv\DoctrineProtoExtractor\Schema\Cardinality;

class OptionalCardinality
{
    public const CARDINALITY_OPTIONAL = 'optional';

    public function __toString()
    {
        return self::CARDINALITY_OPTIONAL;
    }
}
