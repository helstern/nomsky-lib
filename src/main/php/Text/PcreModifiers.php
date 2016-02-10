<?php namespace Helstern\Nomsky\Text;

class PcreModifiers
{
    /** @var string */
    protected $pattern;

    protected $modifiers;

    protected $state = 0;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return PcreModifiers
     */
    public function utf8()
    {
        if ($this->state ^ 1) {
            $this->modifiers .= 'u';
            $this->state |= 1;
        }

        return $this;
    }

    /**
     * @return PcreModifiers
     */
    public function dotAll()
    {
        if ($this->state ^ 2) {
            $this->modifiers .= 's';
            $this->state |= 2;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function toPCREString()
    {
        return '#' . $this->pattern . '#' . $this->modifiers;
    }

    /**
     * @return PcreMatchOne
     */
    public function matchOne()
    {
        $pattern = $this->toPCREString();
        return new PcreMatchOne($pattern);
    }
}
