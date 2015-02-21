<?php namespace Helstern\Nomsky\RegExBuilder;

class RegexAlternativesBuilder extends RegexPatternListBuilder
{
    /**
     * @return string
     */
    public function build()
    {
        $singlePattern = implode('|', $this->patternsList);
        return $singlePattern;
    }

    /**
     * @return RegexPatternListBuilder
     */
    public function copy()
    {
        $copy = new self;
        foreach ($this->patternsList as $pattern) {
            $copy->add($pattern);
        }

        return $copy;
    }

    function __toString()
    {
        return $this->build();
    }
}
