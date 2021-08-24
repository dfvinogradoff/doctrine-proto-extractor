<?php


namespace Dfv\DoctrineProtoExtractor\Schema\Proto3\Types;


use Dfv\DoctrineProtoExtractor\Schema\Proto3\Types\CommonType;

class StringType extends CommonType
{
    public const TYPE_STRING = 'string';

    public function __toString()
    {
        return self::TYPE_STRING;
    }
}
