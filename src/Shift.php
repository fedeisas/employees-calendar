<?php
namespace EmployeesCalendar;

class Shift
{
    const TYPE_DAYTIME = 'daytime';
    const TYPE_NIGHTTIME = 'nighttime';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $weekday;

    /**
     * @param int $weekday
     * @param string $type
     */
    public function __construct($weekday, $type)
    {
        if (!in_array($type, static::types())) {
            throw new \InvalidArgumentException('Wrong shift type: ' . $type);
        }

        if (!is_int($weekday) || !in_array($weekday, range(0, 6))) {
            throw new \InvalidArgumentException('Wrong week day: ' . $weekday);
        }

        $this->weekday = $weekday;
        $this->type = $type;
        $this->name = join(' ', [jddayofweek($weekday - 1, 1), $type]);
    }

    /**
     * @param Shift $shift
     * @return boolean
     */
    public function isEqualTo(Shift $shift)
    {
        return (bool) ($shift->getName() === $this->getName());
    }

    /**
     * @param string $string
     * @return Shift
     */
    public static function createFromString($string)
    {
        $parts = explode(' ', $string);
        if (count($parts) !== 2) {
            throw new \InvalidArgumentException(
                sprintf('Can\'t create Shift from string: %s. Valid format is {weekday} {type}', $string)
            );
        }

        return new static((int) date("w", strtotime($parts[0])), $parts[1]);
    }

    /**
     * @return array
     */
    public static function types()
    {
        return [
            static::TYPE_DAYTIME,
            static::TYPE_NIGHTTIME,
        ];
    }

    /**
     * @return int
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
