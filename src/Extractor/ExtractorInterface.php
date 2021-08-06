<?php


namespace Dfv\DoctrineProtoExtractor\Extractor;

/**
 * Interface ExtractorInterface
 * @package Dfv\DoctrineProtoExtractor\Extractor
 */
interface ExtractorInterface
{
    /**
     * Extract from primary format to common metadata structures
     */
    public function extract(): void;
}
