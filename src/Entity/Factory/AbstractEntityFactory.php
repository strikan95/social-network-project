<?php

namespace App\Entity\Factory;

abstract class AbstractEntityFactory
{
    abstract function getEntityClassName(): string;
    abstract function onCreatePreLoad($source, mixed $target, ?array $settings): void;

    public function buildOrUpdate(object $source, object $target = null, array $settings = null): object
    {
        if(null == $target)
        {
            $target = new ($this->getEntityClassName())();

            $this->onCreatePreLoad($source,  $target, $settings);
        }

        $this->loadFromDto($source, $target);

        return $target;

    }

    private function loadFromDto(object $source, object $target): void
    {
        foreach (get_object_vars($source) as $param => $value)
        {
            if(null == $value)
                continue;

            $method = 'set'.ucwords($param);
            if ($this->methodExistsAndIsCallable($this, $method)) {
                $this->$method($value, $target);
                continue;
            }

            $this->setValue($param, $value, $target);
        }
    }

    private function setValue(string $param, mixed $value, object $target): void
    {
        // Set parameter if setter exists
        $method = 'set'.ucwords($param);
        if ($this->methodExistsAndIsCallable($target, $method))
            $target->$method($value);
    }

    private function methodExistsAndIsCallable($object, $method): bool
    {
        if (method_exists($object, $method) && is_callable([$object, $method]))
            return true;

        return false;
    }
}