<?php namespace Helstern\Nomsky\Text;

class RegexAlternativesPattern implements PcrePattern
{
    /** @var array */
    private $alternatives;

    public function __construct(array $alternatives)
    {
        $this->alternatives = $alternatives;
    }

    /**
     * @return PcreOptions
     */
    public function anchorAtStart()
    {
        $compiled = $this->compile();
        $options = new PcreOptions($compiled);
        $options->anchorAtStart();

        return $options;
    }

    /**
     * @return PcreOptions
     */
    public function anchorAtEnd()
    {
        $compiled = $this->compile();
        $options = new PcreOptions($compiled);
        $options->anchorAtEnd();

        return $options;
    }

    /**
     * @return string
     */
    function compile()
    {
        $compiled = implode('|', $this->alternatives);
        $compiled = '(?:' . $compiled . ')';

        return $compiled;
    }
}
