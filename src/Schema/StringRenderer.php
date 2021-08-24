<?php

namespace Dfv\DoctrineProtoExtractor\Schema;

abstract class StringRenderer implements RenderInterface
{
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param array $collection
     * @return string
     */
    public function stringify(array $collection)
    {
        return implode(PHP_EOL, array_map(static function ($s) {
            return (string)$s;
        }, $collection));
    }
}
