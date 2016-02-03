<?php namespace Helstern\Nomsky\Text;

class PcreOptions
{
    /**
     * @var string
     */
    protected $pattern;

    /** @var int */
    protected $state;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return PcreOptions
     */
    public function anchorAtStart()
    {
        if ($this->state ^ 1) {
            $this->pattern = '^' . $this->pattern;
            $this->state |= 1;
        }

        return $this;
    }

    /**
     * @return PcreOptions
     */
    public function anchorAtEnd()
    {
        if ($this->state ^ 2) {
            $this->pattern = $this->pattern. '$';
            $this->state |= 2;
        }

        return $this;
    }

    /**
     * @return PcreModifiers
     */
    public function dotAll()
    {
        $modifiers = new PcreModifiers($this->pattern);
        $modifiers->dotAll();

        return $modifiers;
    }

    /**
     * @return PcreModifiers
     */
    public function utf8()
    {
        $modifiers = new PcreModifiers($this->pattern);
        $modifiers->utf8();

        return $modifiers;
    }

    /**
     * @return string
     */
    public function toPCREString()
    {
        return $this->pattern;
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
