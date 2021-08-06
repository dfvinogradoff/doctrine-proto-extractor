<?php


namespace Dfv\DoctrineProtoExtractor\Extractor;


use Doctrine\Common\Annotations\AnnotationReader;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class AnnotationExtractor implements ExtractorInterface
{
    /** @var Finder */
    private $finder;
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

        $propertyTraverser = new PropertyTraverser();

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

        $classMap = [];
        foreach ($propertyTraverser->getEntities() as $className => $properties) {
            foreach ($properties as $name => $annotations) {
                foreach ($annotations as $annotation) {
                    $serializer = new CamelCaseToSnakeCaseNameConverter();
                    $groupName = strtr($this->config['denormalizationGroupTemplate'], [
                        '{className}' => $serializer->normalize($className)
                    ]);
                    if ($annotation instanceof Groups) {
                        if (in_array($groupName, $annotation->getGroups(), true)) {
                            // dump($annotation);
                            $classMap[$className][$name] = [];
                        }
                    }
                }
            }
        }
    }
}
