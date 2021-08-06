<?php


namespace Dfv\DoctrineProtoExtractor\Command;


use Dfv\DoctrineProtoExtractor\Extractor\AnnotationExtractor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ExtractCommand extends Command
{
    protected static $defaultName = 'doctrine-proto:extract';

    protected function configure(): void
    {
        $this->setName('doctrine-proto:extract');

        $this
            ->addArgument('config', InputArgument::REQUIRED, 'Absolute or relative path to configuration file. See config.dist.json for example of configuration')
            ->setDescription('Extract Protobuff files from Doctrine entities');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('config');
        $config = new \SplFileInfo($path);

        if (!$config->isReadable()) {
            throw new \Exception("Config file is not readable. Check correct path");
        }

        $configValues = Yaml::parse(file_get_contents($config->getRealPath()));

        $extractor = new AnnotationExtractor(new Finder(), $configValues['doctrine-proto']);
        $extractor->extract();

        return Command::SUCCESS;
    }
}
