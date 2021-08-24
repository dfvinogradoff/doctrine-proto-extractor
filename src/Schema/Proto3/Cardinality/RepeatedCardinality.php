<?php


namespace Dfv\DoctrineProtoExtractor\Schema\Proto3\Cardinality;


use Dfv\DoctrineProtoExtractor\Schema\ApplicableInterface;

/**
 * Class RepeatType
 * @package Dfv\DoctrineProtoExtractor\Schema\Types
 */
class RepeatedCardinality
{
    public const CARDINALITY_REPEATED = 'repeated';

    public function __toString()
    {
        return self::CARDINALITY_REPEATED;
    }
}
