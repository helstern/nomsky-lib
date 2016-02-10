<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TextMatcher;

class WhitespaceMatcher implements TextMatcher
{
    /**
     * @var string
     */
    private $pattern = '#^(?:[[:space:]]|\r\n|\n|\r)+#';

    /**
     * @param $string
     *
     * @return string
     */
    public function match($string)
    {
        $matches = [];
        $nrMatches = preg_match($this->pattern, $string, $matches, PREG_OFFSET_CAPTURE);
        if ($nrMatches > 0) {
            return $matches[0][0];
        }

        return null;
    }
}
