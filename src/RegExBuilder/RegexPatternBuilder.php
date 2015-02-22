<?php namespace Helstern\Nomsky\RegExBuilder;

class RegexPatternBuilder
{
    /** @var string */
    protected $pattern;

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
        return $this->pattern;
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
        $this->pattern = '(?:' . $this->pattern . ')';
        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function repeat()
    {
        $this->pattern = '(?:' . $this->pattern . '+)';

        return $this;
    }

    /**
     * @param string$quote
     * @return RegexPatternBuilder
     */
    public function delimit($quote)
    {
        $this->pattern = $quote . $this->pattern . $quote;
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
}
