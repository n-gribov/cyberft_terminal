<?php

namespace common\models;

final class Time
{
    private $hours;
    private $minutes;
    private $seconds;

    public function __construct(string $value)
    {
        if (preg_match('/(\d+):(\d+)(:(\d+))?/', $value, $matches)) {
            $hours = intval($matches[1]);
            $minutes = intval($matches[2]);
            $seconds = intval($matches[4] ?? 0);
            if ($hours > 23 || $minutes > 59 || $seconds > 59) {
                throw new \InvalidArgumentException("Invalid value $value");
            }
            $this->hours = $hours;
            $this->minutes = $minutes;
            $this->seconds = $seconds;
        } else {
            throw new \InvalidArgumentException("Invalid value $value");
        }
    }

    public function greaterThan(Time $other): bool
    {
        return $this->secondsFromMidnight() > $other->secondsFromMidnight();
    }

    public function greaterThanOrEqualTo(Time $other): bool
    {
        return $this->secondsFromMidnight() >= $other->secondsFromMidnight();
    }

    public function lessThan(Time $other): bool
    {
        return $this->secondsFromMidnight() < $other->secondsFromMidnight();
    }

    public function lessThanOrEqualTo(Time $other): bool
    {
        return $this->secondsFromMidnight() <= $other->secondsFromMidnight();
    }

    private function secondsFromMidnight(): int
    {
        return $this->hours * 3600 + $this->minutes * 60 + $this->seconds;
    }
    
    public function hours(): int
    {
        return $this->hours;
    }

    public function minutes(): int
    {
        return $this->minutes;
    }

    public function seconds(): int
    {
        return $this->seconds;
    }
}
