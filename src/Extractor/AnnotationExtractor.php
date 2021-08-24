<?php


namespace Dfv\DoctrineProtoExtractor\Extractor;


use Dfv\DoctrineProtoExtractor\Schema\Proto3\Cardinality\RepeatedCardinality;
use Dfv\DoctrineProtoExtractor\Schema\Proto3\Field;
use Dfv\DoctrineProtoExtractor\Schema\Proto3\Message;
use Dfv\DoctrineProtoExtractor\Schema\Proto3\Proto;
use Dfv\DoctrineProtoExtractor\Schema\Proto3\Types\MessageType;
use Dfv\DoctrineProtoExtractor\Writer\PlantUMLWriter\PlantUMLWriter;
use Dfv\DoctrineProtoExtractor\Writer\Proto3Writer\Proto3Writer;
use Doctrine\ORM\Mapping\Column;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\Finder;

class AnnotationExtractor implements ExtractorInterface
{
    /** @var Finder */
    private $finder;

    /** @var array{from: string, denormalizationGroupTemplate: string, protoVersion: string, package: string, filename: string, service: string}  */
    private $config = [];

    public function __construct(Finder $finder, array $config)
    {
        $this->finder = $finder;
        $this->config = $config;
    }

    public function extract(): void
    {
        $finder = clone $this->finder;
        $finder
            ->files()
            ->in($this->config['from'])
            ->name('*.php');

        $propertyTraverser = new PropertyVisitor();
        $propertyTraverser->setGroupName($this->config['denormalizationGroupTemplate']);

        $traverser = new NodeTraverser();
        $traverser->addVisitor($propertyTraverser);

        foreach ($finder as $file) {
            $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
            try {
                $ast = $parser->parse($file->getContents());
                $traverser->traverse($ast);
            } catch (Error $error) {
                echo "Parse error: {$error->getMessage()}\n";
                return;
            }
        }

        // Just one type of Writer implemented
        $writer = new Proto3Writer($propertyTraverser, $this->config['service'], $this->config['goPackage'], $this->config['package'], $this->config['protoVersion']);
        $output = $writer->write();

        file_put_contents(getcwd()."/".$this->config['filename'], $output);

        $writer = new PlantUMLWriter($propertyTraverser, $this->config['service'], $this->config['goPackage'], $this->config['package'], $this->config['protoVersion']);
        $output = $writer->write();

        file_put_contents(getcwd()."/".$this->config['filename'].'.uml_schema', $output);
    }
}