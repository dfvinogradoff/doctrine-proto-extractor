<?php

namespace Dfv\DoctrineProtoExtractor\Schema;

abstract class StringRenderer implements RenderInterface
{
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Make string from array of elements. Helper function
     * @param array<StringerInterface> $collection
     * @return string
     */
    public function stringify(array $collection): string
    {
        return implode(PHP_EOL, array_map(static function ($s) {
            return (string)$s;
        }, $collection));
    }
}
