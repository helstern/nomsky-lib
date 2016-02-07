<?php namespace Helstern\Nomsky\Text;

class RegexPattern implements PcrePattern
{
    /**
     * @var string
     */
    private $regexString;

    /**
     * @param string $regexString
     */
    public function __construct($regexString)
    {
        $this->regexString = $regexString;
    }

    /**
     * @return PcreOptions
     */
    public function anchorAtStart()
    {
        $options = new PcreOptions($this->regexString);
        $options->anchorAtStart();

        return $options;
    }

    /**
     * @return PcreOptions
     */
    public function anchorAtEnd()
    {
        $options = new PcreOptions($this->regexString);
        $options->anchorAtEnd();

        return $options;
    }

    /**
     * @return string
     */
    function compile()
    {
        return $this->regexString;
    }
}
