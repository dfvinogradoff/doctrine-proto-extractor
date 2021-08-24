<?php


namespace Dfv\DoctrineProtoExtractor\Schema\Proto3;

use Dfv\DoctrineProtoExtractor\Schema\Proto3\Message;
use Dfv\DoctrineProtoExtractor\Schema\StringRenderer;
use function Dfv\DoctrineProtoExtractor\Schema\strtr;

class Proto extends StringRenderer
{
    /** @var array<Message> */
    private $messages = [];

    /** @var string */
    private $protoVersion = 'proto3';

    /** @var */
    private $package;

    private $goPackage;

    /** @var string */
    private $service;

    /** @var array<Method> */
    private $methods;

    /**
     * @return array<Message>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array<Message> $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return string
     */
    public function getProtoVersion(): string
    {
        return $this->protoVersion;
    }

    /**
     * @param string $protoVersion
     */
    public function setProtoVersion(string $protoVersion): void
    {
        $this->protoVersion = $protoVersion;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param mixed $package
     */
    public function setPackage($package): void
    {
        $this->package = $package;
    }

    /**
     * @param Message $message
     */
    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    public function render(): string
    {
        $contents = file_get_contents(__DIR__ . '/../../Writer/Proto3Writer/proto.tpl');
        return \strtr($contents, [
            '{protoVersion}' => $this->getProtoVersion(),
            '{service}' => $this->getService(),
            '{methods}' => $this->stringify($this->getMethods()),
            '{package}' => $this->getPackage(),
            '{goPackage}' => $this->getGoPackage(),
            '{messages}' => $this->stringify($this->getMessages())
        ]);
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $this->service = $service;
    }

    /**
     * @return array<Method>
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param array<Method> $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

    /**
     * @return mixed
     */
    public function getGoPackage()
    {
        return $this->goPackage;
    }

    /**
     * @param mixed $goPackage
     */
    public function setGoPackage($goPackage): void
    {
        $this->goPackage = $goPackage;
    }
}