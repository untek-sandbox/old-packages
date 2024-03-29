<?php

namespace Untek\Sandbox\Sandbox\Apache\Domain\Repositories\Conf;

use Untek\Core\FileSystem\Helpers\FileStorageHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Sandbox\Sandbox\Apache\Domain\Entities\HostEntity;
use Untek\Sandbox\Sandbox\Apache\Domain\Entities\ServerEntity;
use Untek\Sandbox\Sandbox\Apache\Domain\Helpers\HostsParser;

class HostsRepository
{

    private static $collection = null;

    public function findOneByName(string $name)
    {
        $collection = $this->getIndexedCollection();
        if (!$collection->has($name)) {
            throw new NotFoundException('Host not found!');
        }
        return $collection->get($name);
    }

    /**
     * @return Enumerable | ServerEntity[]
     */
    private function getIndexedCollection(): Enumerable
    {
        if (self::$collection == null) {
            $hostsContent = FileStorageHelper::load('/etc/hosts');
            preg_match_all("/#\s*<([a-zA-Z_-]+)([^>]*)>([\s\S]+?)#\s*<\/([a-zA-Z_-]+)>/i", $hostsContent, $matches);
            $collection = [];
            $all = [];
            foreach ($matches[0] as $index => $value) {
                $item = [];
                $item['tagName'] = $matches[1][$index];
                $hostsCollection = HostsParser::parse($matches[3][$index]);
                foreach ($hostsCollection as &$host) {
                    $host['categoryName'] = $item['tagName'];
                    $collection[$host['host']] = $host;
                }
            }
            self::$collection = CollectionHelper::create(HostEntity::class, $collection);
        }
        return self::$collection;
    }

    function all(): Enumerable
    {
        $commonTagCollection = $this->getIndexedCollection();
        return $commonTagCollection;
    }

    private function getTitleFromReadme(string $documentRoot): string
    {
        $readmeMd = $documentRoot . '/README.md';
        $readmeMdTitle = '';
        if (file_exists($readmeMd)) {
            $readmeMdLines = file($readmeMd);
            $readmeMdTitle = ltrim($readmeMdLines[0], ' #');
            $readmeMdTitle = trim($readmeMdTitle);
        }
        return $readmeMdTitle;
    }

}
