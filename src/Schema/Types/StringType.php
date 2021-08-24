<?php


namespace Dfv\DoctrineProtoExtractor\Schema\Types;


class StringType extends CommonType
{
    public const TYPE_STRING = 'string';

    public function __toString()
    {
        return self::TYPE_STRING;
    }
}
