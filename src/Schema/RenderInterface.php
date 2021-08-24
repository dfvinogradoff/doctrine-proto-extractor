<?php

namespace Dfv\DoctrineProtoExtractor\Schema;

/**
 * Render inner struct into proto-files
 */
interface RenderInterface
{
    public function render(): string;
}
