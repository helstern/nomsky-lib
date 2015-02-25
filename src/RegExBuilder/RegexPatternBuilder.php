<?php namespace Helstern\Nomsky\RegExBuilder;

class RegexPatternBuilder
{
    /** @var string */
    protected $pattern;

    /** @var array */
    protected $modifiers = array();

    /**
     * @param string $basePattern
     * @return RegexPatternBuilder
     */
    public static function newInstance($basePattern)
    {
        return new self($basePattern);
    }

    /**
     * @param string $basePattern
     */
    public function __construct($basePattern)
    {
        $this->pattern = $basePattern;
    }

    /**
     * @return string
     */
    public function build()
    {
        ksort($this->modifiers);
        $finalPattern = $this->pattern;
        foreach ($this->modifiers as $formatPattern) {
            $finalPattern = sprintf($formatPattern, $finalPattern);
        }

        return $finalPattern;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function negativeLookAhead()
    {
        $modifierKey = $this->createModifierKey('%04d', 'negativeLookAhead');
        $this->modifiers[$modifierKey] = '(?!'. '%s'. ')';

        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function lazy()
    {
        $modifierKey = $this->createModifierKey('%04d', 'lazy');
        $this->modifiers[$modifierKey] = '%s' . '?';

        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function quote()
    {
        $this->pattern = preg_quote($this->pattern);

        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function group()
    {
        $modifierKey = $this->createModifierKey('%04d', 'group');
        $this->modifiers[$modifierKey] = '(?:' . '%s' . ')';
        return $this;
    }

    public function repeatZeroOrMore()
    {
        $modifierKey = $this->createModifierKey('%04d', 'repeat zero or more');
        $this->modifiers[$modifierKey] = '%s' . '*';

        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function repeatOnceOrMore()
    {
        $modifierKey = $this->createModifierKey('%04d', 'repeat one or more');
        $this->modifiers[$modifierKey] = '%s' . '+';

        return $this;
    }

    /**
     * @param string$quote
     * @return RegexPatternBuilder
     */
    public function delimit($quote)
    {
        $modifierKey = $this->createModifierKey('%04d', 'delimit');
        $this->modifiers[$modifierKey] = $quote . '%s' . $quote;

        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function copy()
    {
        return clone $this;
    }

    function __toString()
    {
        return $this->build();
    }

    /**
     * @param string $modifierUniqueIndexFormat sprintf number format
     * @param string $modifierType
     * @return string
     */
    protected function createModifierKey($modifierUniqueIndexFormat, $modifierType)
    {
        $modifierKey = sprintf($modifierUniqueIndexFormat, count($this->modifiers)) . '-' . $modifierType;
        return $modifierKey;
    }
}
