<?php namespace Helstern\Nomsky\RegExBuilder;

class RegexCharacterSetBuilder
{
    /** @var array */
    protected $characters;

    protected $patternFormat = '[%s]';

    /**
     * @param string $rangeStart
     * @param string $rangeEnd
     * @return RegexPatternBuilder
     */
    public static function newInstanceFromRange($rangeStart, $rangeEnd)
    {
        $instance = new self();
        $instance->addRange($rangeStart, $rangeEnd);

        return $instance;
    }

    /**
     * @param $string
     * @return RegexCharacterSetBuilder
     */

    public static function newInstanceFromString($string)
    {
        $instance = new self();
        $instance->addCharacters($string);

        return $instance;
    }

    protected function __construct()
    {}

    /**
     * @return string
     */
    public function build()
    {
        $built = (string) $this->pattern();
        return $built;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function pattern()
    {
        $finalPattern = implode('', $this->characters);
        $finalPattern = sprintf($this->patternFormat, $finalPattern);

        $patternBuilder = new RegexPatternBuilder($finalPattern);
        return $patternBuilder;
    }

    /**
     * @return RegexCharacterSetBuilder
     */
    public function not()
    {
        $this->patternFormat = '[^%s]';

        return $this;
    }

    /**
     * @param string $rangeStart
     * @param string $rangeEnd
     * @return $this
     */
    public function addRange($rangeStart, $rangeEnd)
    {
        $this->characters[] = $rangeStart . '-' . $rangeEnd;

        return $this;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function addCharacters($string)
    {
        $this->characters[] = $string;

        return $this;
    }
}
