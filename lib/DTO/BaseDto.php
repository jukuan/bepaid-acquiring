<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO;

use DateTimeImmutable;
use Exception;

abstract class BaseDto
{
    protected const DATE_FIELDS = [
        'created_at',
        'renew_at',
        'active_to',
    ];

    abstract public static function createFromArray($fields): BaseDto;

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            if ($this->isDateField($name)) {
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

    protected function isDateField(string $name): bool
    {
        return in_array($name, self::DATE_FIELDS, true)
            || str_contains($name, '_at');
    }
}
