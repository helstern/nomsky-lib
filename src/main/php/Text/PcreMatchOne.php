<?php namespace Helstern\Nomsky\Text;

class PcreMatchOne
{
    /** @var string */
    protected $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param string $string
     * @param int $offset
     *
     * @return null|string
     */
    public function firstAtOffset($string, $offset)
    {
        $matches = [];
        $nrMatches = preg_match($this->pattern, $string, $matches, PREG_OFFSET_CAPTURE);

        if ($nrMatches > 0 && $matches[0][0] == $offset) {
            return $matches[0][0];
        }

        return null;
    }

    /**
     * @param $string
     *
     * @return null|string
     */
    public function firstAnywhere($string)
    {
        $matches = [];
        $nrMatches = preg_match($this->pattern, $string, $matches, 0);

        if ($nrMatches > 0) {
            return $matches[0];
        }

        return null;
    }
}
