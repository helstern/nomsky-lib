<?php namespace Helstern\Nomsky\String;

use Helstern\Nomsky\Text\String\StringMatch;
use Helstern\Nomsky\Text\DeprecatedTextMatcher;
use Helstern\Nomsky\Text\TextMatch;

class StringPrefixMatcher implements DeprecatedTextMatcher
{
    /** @var string  */
    protected $prefix;

    public function __construct ($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $string
     * @return TextMatch
     */
    public function match($string)
    {
        if (0 === mb_strpos($string, $this->prefix, 0, 'UTF-8')) {
            $matchedText = mb_substr($string, 0, mb_strlen($this->prefix));
            return new StringMatch($matchedText);
        }

        return null;
    }
}
