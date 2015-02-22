<?php namespace Helstern\Nomsky\RegExBuilder;

abstract class RegexPatternListBuilder
{
    protected $patternsList = array();

    public function addMany()
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
    public function implode()
    {
        return new RegexPatternBuilder($this->build());
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
     * @return RegexPatternListBuilder
     */
    public function groupEach()
    {
        $this->patternsList = array_map(
            function ($pattern) {
                $builder = new RegexPatternBuilder($pattern);
                return $builder->group()->build();
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
