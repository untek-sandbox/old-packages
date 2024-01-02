<?php

namespace Untek\Sandbox\Sandbox\Deployer\Domain\Tasks\Zn;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Sandbox\Sandbox\Deployer\Domain\Repositories\Shell\ZnShell;

class ZnMigrateUpTask extends BaseShell implements TaskInterface
{

    public $env = null;

    public function getTitle(): ?string
    {
        if ($this->env) {
            return 'Zn. Migrate up for "{{env}}" environment';
        } else {
            return 'Zn. Migrate up for default environment';
        }
    }
    
    public function run()
    {
//        $profileName = VarProcessor::get('currentProfile');
//        $profileConfig = ProfileRepository::findOneByName($profileName);

        $zn = new ZnShell($this->remoteShell);
        $zn->setBinDirectory(ShellFactory::getVarProcessor()->get('releasePath'));
        $zn->migrateUp($this->env);

        /*try {
            $zn->migrateUp($envName);
        } catch (\Throwable $e) {
            $fs = new FileSystemShell($this->remoteShell);
            $fs->sudo()->chmod($profileConfig['releasePath']. '/var', 'a+w', true);
            $zn->migrateUp($envName);
        }*/
    }

    protected function migrateUp(string $envName)
    {

    }
}
