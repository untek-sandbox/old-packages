<?php

namespace Untek\Tool\Dev\Composer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Tool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface;
use Untek\Tool\Package\Domain\Entities\ConfigEntity;
use Untek\Tool\Package\Domain\Helpers\ComposerConfigHelper;
use Untek\Tool\Package\Domain\Interfaces\Services\GitServiceInterface;
use Untek\Tool\Package\Domain\Libs\Depend;

class ComposerConfigCommand extends Command
{

    protected static $defaultName = 'composer:config:dependency-version';

    protected $configService;
    protected $gitService;

    public function __construct(ConfigServiceInterface $configService, GitServiceInterface $gitService)
    {
        parent::__construct(self::$defaultName);
        $this->configService = $configService;
        $this->gitService = $gitService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Composer dependency version</>');
        $output->writeln('');
        /** @var ConfigEntity[] | Enumerable $collection */
        $collection = $this->configService->findAll();
        /** @var ConfigEntity[] | Enumerable $collection */
        $thirdPartyCollection = $this->configService->allWithThirdParty();
        $namespacesPackages = ComposerConfigHelper::extractPsr4AutoloadPackages($thirdPartyCollection);

        $output->writeln('<fg=white>Get packages version...</>');
        $output->writeln('');
        $lastVersions = $this->gitService->lastVersionCollection();

        if ($collection->count() == 0) {
            $output->writeln('<fg=magenta>Not found packages!</>');
            $output->writeln('');
            return 0;
        }

        $output->writeln('<fg=white>Get packages info...</>');
        $output->writeln('');

        $progressBar = new ProgressBar($output);
        $progressBar->setMaxSteps($collection->count());
        $progressBar->start();
        $depend = new Depend($namespacesPackages, $lastVersions);
        $deps = $depend->all($collection, function () use ($progressBar) {
            $progressBar->advance();
        });
        $progressBar->finish();

        $output->writeln('');
        $output->writeln('');

        foreach ($deps as $depId => $dep) {
            $output->writeln('<fg=magenta># ' . $depId . '</>');
            $output->writeln('');
            $output->writeln(json_encode($dep, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        return 0;
    }

}
