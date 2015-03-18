<?php namespace Helstern\Nomsky\TextMatch;

class RegexPatternBuilder
{
    protected $patternsMap = array();

    /**
     * @param string $patternName
     * @param string $unquotedPattern
     */
    public function addNamedPattern($patternName, $unquotedPattern)
    {
        $quotedPattern = preg_quote($unquotedPattern, '#');
        $this->patternsMap[$patternName] = $quotedPattern;
    }

    public function build()
    {
        reset($this->patternsMap);
        $nrPatterns = count($this->patternsMap);

        $patternName = 'pattern' . key($this->patternsMap);
        $patternDefinition = current($this->patternsMap);
        next($this->patternsMap);

        $patternString = "(?P<$patternName>$patternDefinition)";

        for ($i = 1; $i < $nrPatterns; $i++) {
            $patternName = 'pattern' . key($this->patternsMap);
            $patternDefinition = current($this->patternsMap);

            $patternString .= "|(?P<$patternName>$patternDefinition)";
            next($this->patternsMap);
        }

        $patternString = '#' . $patternString . '#';
        $patternString .= 'm'; //PCRE_MULTILINE
        $patternString .= 's'; //PCRE_DOTALL
        $patternString .= 'u'; //utf-8

        return $patternString;
    }
}
