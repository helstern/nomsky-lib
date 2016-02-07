<?php namespace Helstern\Nomsky\Regex;

abstract class RegexPatternListBuilder
{
    protected $patternsList = array();

    public function addAll()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->add($arg);
        }

        return $this;
    }

    /**
     * @return string
     */
    abstract public function build();

    /**
     * @return RegexPatternBuilder
     */
    public function pattern()
    {
        $basePattern = $this->build();
        $regexBuilder =  new RegexPatternBuilder($basePattern);

        return $regexBuilder;
    }

    /**
     * @param string $pattern
     * @return RegexPatternListBuilder
     */
    public function add($pattern)
    {
        $this->patternsList[] = $pattern;
        return $this;
    }

    /**
     * @return RegexPatternBuilder
     */
    public function nonCapturingGroup()
    {
        $basePattern = $this->build();
        $regexBuilder = new RegexPatternBuilder($basePattern);
        $regexBuilder->nonCapturingGroup();

        return $regexBuilder;
    }

    /**
     * @return RegexPatternListBuilder
     */
    public function groupEach()
    {
        $this->patternsList = array_map(
            function ($pattern) {
                $builder = new RegexPatternBuilder($pattern);
                return $builder->nonCapturingGroup()->build();
            },
            $this->patternsList
        );

        return $this;
    }

    /**
     * @return array
     */
    public function toList()
    {
        return $this->patternsList;
    }


    /**
     * @return RegexPatternListBuilder
     */
    abstract public function copy();
}
