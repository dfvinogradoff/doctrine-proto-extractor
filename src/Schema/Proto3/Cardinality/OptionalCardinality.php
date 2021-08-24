<?php

namespace Dfv\DoctrineProtoExtractor\Schema\Proto3\Cardinality;

class OptionalCardinality
{
    public const CARDINALITY_OPTIONAL = 'optional';

    public function __toString()
    {
        return self::CARDINALITY_OPTIONAL;
    }
}
