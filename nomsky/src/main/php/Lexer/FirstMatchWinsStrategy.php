<?php namespace Helstern\Nomsky\Lexer;

class FirstMatchWinsStrategy implements TokenMatchStrategy
{
    /**
     * @param TextReader $reader
     * @param array|TokenMatchReader[] $tokenMatchersList
     *
     * @return TokenMatch|null
     */
    public function match(TextReader $reader, array $tokenMatchersList)
    {
        //verify reader has not gone past EOF

        foreach ($tokenMatchersList as $matcher) {
            $nextMatch = $matcher->read($reader);
            if (!is_null($nextMatch)) {
                return $nextMatch;
            }
        }

        return null;
    }
}
