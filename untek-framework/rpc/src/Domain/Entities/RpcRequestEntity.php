<?php

namespace Untek\Framework\Rpc\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Framework\Rpc\Domain\Enums\RpcVersionEnum;
use Untek\Framework\Rpc\Domain\Exceptions\ParamNotFoundException;

class RpcRequestEntity implements EntityIdInterface, ValidationByMetadataInterface
{

    private $jsonrpc = RpcVersionEnum::V2_0;
    private $method = '';
    private $params = null;
    private $meta = null;
    private $id = null;

    public function __construct(string $method = '', $params = null, $meta = null, ?int $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->meta = $meta;
        $this->id = $id;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('method', new Assert\NotBlank);
        $metadata->addPropertyConstraint('jsonrpc', new Assert\NotBlank);
    }

    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    public function setJsonrpc(string $jsonrpc): void
    {
        $this->jsonrpc = trim($jsonrpc);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = trim($method);
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function getParamItem(string $key, $default = null)
    {
        if (!empty($this->params) && array_key_exists($key, $this->params)) {
            if (!empty($this->params[$key])) {
                return $this->params[$key];
            }
        }
        if(func_get_args() > 1) {
            return $default;
        }
        $message = "Param \"$key\" not found";
        throw new ParamNotFoundException($message);
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function setParamItem(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function getMeta(): ?array
    {
        return $this->meta;
    }

    public function setMetaItem(string $key, string $value)
    {
        return ArrayHelper::setValue($this->meta, $key, $value);
    }

    public function getMetaItem(string $key, $default = null)
    {
        if (empty($this->meta)) {
            return $default;
        }
        return ArrayHelper::getValue($this->meta, $key, $default);
    }

    public function addMeta(string $key, $value): void
    {
        if (!is_array($this->meta)) {
            $this->meta = [];
        }
        $this->meta[$key] = $value;
    }

    public function setMeta(array $meta): void
    {
        $this->meta = $meta;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }
}
