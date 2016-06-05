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

        if (!$this->constraintsTypeCheck($constraints)) {
            throw new \InvalidArgumentException('Employee constraints should be an instance of Shift.');
        }

        $this->name = $name;
        $this->constraints = $constraints;
        $this->id = spl_object_hash($this); // Auto generate employee id, not performant but who cares
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
        return count($constraints) === count(array_filter($constraints, function ($constraint) {
            return $constraint instanceof Shift;
        }));
    }
}
