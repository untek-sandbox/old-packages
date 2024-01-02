<?php

namespace Untek\Tool\Test\Traits;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Rpc\Domain\Libs\RpcFixtureProvider;

trait FixtureTrait
{

//    use BaseUrlTrait;
    use ProviderTrait;
    
    private $fixtures = [];
    private $fixtureProvider;

    protected function fixtures(): array
    {
        return [];
    }
    
    protected function addFixtures(array $fixtures)
    {
        $this->fixtures = ArrayHelper::merge($this->fixtures, $fixtures);
    }

    protected function importFixture() {
        $this->addFixtures($this->fixtures());
        if ($this->fixtures) {
            $this->getFixtureProvider(getenv('RPC_URL'))->import($this->fixtures);
        }
    }

    protected function setUp(): void
    {
        $this->setBaseUrl(getenv('RPC_URL'));
        $this->initFixtureProvider(getenv('RPC_URL'));
        $this->importFixture();
        parent::setUp();
    }

    public function initFixtureProvider(string $baseUrl): void
    {
        if(empty($this->fixtureProvider)) {
            $this->fixtureProvider = new RpcFixtureProvider($this->getRpcProvider($baseUrl));
        }
    }
    
    public function getFixtureProvider(string $baseUrl = null): RpcFixtureProvider
    {
        /*if(empty($this->fixtureProvider)) {
            $this->fixtureProvider = new RpcFixtureProvider($this->getRpcProvider($baseUrl));
        }*/
        return $this->fixtureProvider;
    }
}