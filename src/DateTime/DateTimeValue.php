<?php declare(strict_types=1);

namespace Slepic\ValueObject\DateTime;

use Slepic\ValueObject\Type\Downcasting\ToStringConvertibleInterface;
use Slepic\ValueObject\Type\TypeViolation;
use Slepic\ValueObject\ViolationException;

class DateTimeValue implements \JsonSerializable, ToStringConvertibleInterface
{
    protected const FORMAT = \DATE_ATOM;
    protected const TIMEZONE = 'UTC';

    private \DateTimeImmutable $value;

    private function __construct(\DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    final public static function timezone(): \DateTimeZone
    {
        return new \DateTimeZone(static::TIMEZONE);
    }

    /**
     * @param string $value
     * @return static
     * @throws \Slepic\ValueObject\ViolationExceptionInterface
     */
    public static function fromString(string $value): self
    {
        try {
            $dateTime = new \DateTimeImmutable($value, static::timezone());
        } catch (\Exception $e) {
            throw DateTimeFormatViolation::exception(static::FORMAT);
        }

        return new static($dateTime);
    }

    /**
     * @param string $format
     * @param string $value
     * @return static
     * @throws \Slepic\ValueObject\ViolationExceptionInterface
     */
    final public static function fromFormat(string $format, string $value): self
    {
        $dateTime = \DateTimeImmutable::createFromFormat($format, $value);

        if ($dateTime === false) {
            throw DateTimeFormatViolation::exception($format);
        }

        return new static($dateTime);
    }

    final public static function fromDateTimeImmutable(\DateTimeImmutable $value): self
    {
        if ($value->getTimezone()->getName() !== 'UTC') {
            $value = $value->setTimezone(static::timezone());
        }
        return new static($value);
    }

    final public static function fromDateTime(\DateTime $value): self
    {
        $immutable = \DateTimeImmutable::createFromMutable($value);
        return static::fromDateTimeImmutable($immutable);
    }

    final public static function fromDateTimeInterface(\DateTimeInterface $value): self
    {
        $dateTime = new \DateTimeImmutable('now', static::timezone());
        $dateTime = $dateTime->setTimestamp($value->getTimestamp());
        return new static($dateTime);
    }

    /**
     * @param object $value
     * @return static
     * @throws \Slepic\ValueObject\ViolationExceptionInterface
     */
    public static function fromObject(object $value): self
    {
        if ($value instanceof \DateTimeImmutable) {
            return static::fromDateTimeImmutable($value);
        }

        if ($value instanceof \DateTime) {
            return static::fromDateTime($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return static::fromDateTimeInterface($value);
        }

        throw ViolationException::for(new TypeViolation());
    }

    final public function __toString(): string
    {
        return $this->value->format(static::FORMAT);
    }

    final public function jsonSerialize(): string
    {
        return (string) $this;
    }

    final public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->value;
    }

    final public function getOffset(): int
    {
        return $this->value->getOffset();
    }

    final public function getTimestamp(): int
    {
        return $this->value->getTimestamp();
    }

    final public function setTimestamp(int $timestamp): self
    {
        return new static($this->value->setTimestamp($timestamp));
    }

    final public function getTimezone(): \DateTimeZone
    {
        return $this->value->getTimezone();
    }

    final public function format($format): string
    {
        return $this->value->format($format);
    }

    final public function diff($datetime2, $absolute = false): \DateInterval
    {
        if (!$datetime2 instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException();
        }

        if (!\is_bool($absolute)) {
            throw new \InvalidArgumentException();
        }

        return $this->value->diff($datetime2, $absolute);
    }

    final public function modify(string $modifier): self
    {
        return new static($this->value->modify($modifier));
    }

    final public function add(\DateInterval $interval): self
    {
        return new static($this->value->add($interval));
    }

    final public function sub(\DateInterval $interval): self
    {
        return new static($this->value->sub($interval));
    }
}
