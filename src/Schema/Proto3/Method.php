<?php

namespace Dfv\DoctrineProtoExtractor\Schema\Proto3;

use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;
use function Dfv\DoctrineProtoExtractor\Schema\strtr;

class Method extends StringRenderer
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function render(): string
    {
        $content = file_get_contents(__DIR__ . "/../../Writer/Proto3Writer/method.tpl");
        return \strtr($content, [
            '{message}' => $this->message
        ]);
    }
}