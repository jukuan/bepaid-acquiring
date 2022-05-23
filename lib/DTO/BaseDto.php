<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO;

use DateTimeImmutable;
use Exception;

abstract class BaseDto
{
    abstract public static function createFromArray($fields): BaseDto;

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if (str_contains($name, '_at')) {
                try {
                    $value = new DateTimeImmutable($value);
                }catch (Exception $e) {
                }
            }

            try {
                $this->{$name} = $value;
            } catch (Exception $e) {
            }
        }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return null;
    }

    protected static function toDateTime(string $dateAndTime): ?DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($dateAndTime);
        } catch (Exception $e) {
            return null;
        }
    }
}
