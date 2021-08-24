<?php


namespace Dfv\DoctrineProtoExtractor\Writer;

/**
 * Interface WriterInterface
 * @package Dfv\DoctrineProtoExtractor\Writer
 */
interface WriterInterface
{
    /**
     * @return mixed
     */
    public function write(): string;

    /**
     * Supports or not writer
     * @param $syntax
     * @return bool
     */
    public function supports($syntax): bool;
}
