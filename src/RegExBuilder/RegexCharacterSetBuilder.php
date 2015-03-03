<?php namespace Helstern\Nomsky\RegExBuilder;

class RegexCharacterSetBuilder
{
    /** @var array */
    protected $sets;

    protected $patternFormat = '[%s]';

    /**
     * @param string $rangeStart
     * @param string $rangeEnd
     * @return RegexCharacterSetBuilder
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
        $finalPattern = implode('', $this->sets);
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
     * @return RegexCharacterSetBuilder
     */
    public function addRange($rangeStart, $rangeEnd)
    {
        $this->sets[] = $rangeStart . '-' . $rangeEnd;

        return $this;
    }

    /**
     * @param string $string
     * @return RegexCharacterSetBuilder
     */
    public function addCharacters($string)
    {
        $this->sets[] = $string;

        return $this;
    }

    /**
     * @param $metaString
     * @return RegexCharacterSetBuilder
     */
    public function addMeta($metaString)
    {
        $this->sets[] = $metaString;

        return $this;
    }

    /**
     * @return RegexCharacterSetBuilder
     */
    public function addDigits()
    {
        $this->addRange('0', '9');

        return $this;
    }

    /**
     * @param string $case
     * @return RegexCharacterSetBuilder
     */
    public function addLetters($case = 'upper')
    {
        if ($case == 'upper') {
            $this->addRange('A', 'Z');
        } else {
            $this->addRange('a', 'z');
        }

        return $this;
    }

    /**
     * @return RegexCharacterSetBuilder
     */
    public function addMetaPunctuation()
    {
        $this->addMeta('[:punct:]');

        return $this;
    }

    public function __toString()
    {
        return $this->build();
    }

}
