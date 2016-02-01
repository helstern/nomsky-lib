<?php namespace Helstern\Nomsky\Text\String;

use Helstern\Nomsky\Text\PcrePattern;
use Helstern\Nomsky\Text\DeprecatedTextMatcher;

class AnchoredPcreMatcher implements DeprecatedTextMatcher
{
    /** @var PcrePattern */
    protected $pattern;

    public function __construct(PcrePattern $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param string $string
     * @return StringMatch
     */
    public function match ($string)
    {
        $matcher = $this->getOrCreateMatcher();
        $matchedText = $matcher->firstAtOffset($string, 0);
        if (!is_null($matchedText)) {
            return new StringMatch($matchedText);
        }

        return null;
    }

    protected function getOrCreateMatcher()
    {
        //disable PCRE_MULTILINE because it impacts anchoring
        //$modifiers .= 'm'; //PCRE_MULTILINE

        $modifiers = $this->pattern->anchorAtStart();
        $matcher = $modifiers->dotAll()->utf8()->matchOne();
        return $matcher;
    }
}
