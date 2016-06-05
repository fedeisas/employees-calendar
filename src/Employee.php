<?php
namespace EmployeesCalendar;

class Employee
{
    /**
     * @var string
     */
    protected $generatedId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Shit[]
     */
    protected $constraints;

    /**
     * @param string $name
     * @param Shift[] $constraints
     */
    public function __construct($name = null, array $constraints = [])
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Employee should have name.');
        }

        if (!empty($constraints) && !$this->constraintsTypeCheck($constraints)) {
            throw new \InvalidArgumentException('Employee constraints should be an instance of Shift.');
        }

        $this->name = $name;
        $this->constraints = $constraints;
        $this->id = $this->generateId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Shift $shift
     * @return bool
     */
    public function canWork(Shift $shift)
    {
        foreach ($this->constraints as $constraint) {
            if ($constraint->isEqualTo($shift)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $constraints
     * @return bool
     */
    protected function constraintsTypeCheck(array $constraints = [])
    {
        $types = array_unique(array_map('get_class', $constraints));

        if (count($types) === 1 && $types[0] === Shift::class) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function generateId()
    {
        return uniqid();
    }
}
