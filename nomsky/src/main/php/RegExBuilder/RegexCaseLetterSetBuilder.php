<?php namespace Helstern\Nomsky\RegExBuilder;

class RegexCaseLetterSetBuilder
{
    protected $case = 'lower';

    /**
     * @return RegexCaseLetterSetBuilder
     */
    public function lower()
    {
        $this->case = 'lower';

        return $this;
    }

    /**
     * @return RegexCaseLetterSetBuilder
     */
    public function upper()
    {
        $this->case = 'upper';

        return $this;
    }

    /**
     * @return RegexCaseLetterSetBuilder
     */
    public function all()
    {
        $this->case = 'all';

        return $this;
    }

    /**
     * @return RegexCharacterSetBuilder
     */
    public function pattern()
    {
        switch ($this->case) {
            case 'lower':
                return RegexCharacterSetBuilder::newInstanceFromRange('a', 'z');
            case 'upper':
                return RegexCharacterSetBuilder::newInstanceFromRange('A', 'Z');
        }
        //all
        $instance = RegexCharacterSetBuilder::newInstanceFromRange('a', 'z');
        $instance->addRange('A', 'Z');
        return $instance;
    }

    public function build()
    {
        return (string) $this;
    }

    function __toString()
    {
        return (string) $this->build();
    }
}
