<?php namespace Helstern\Nomsky\Tokens;

interface TokenEqualityTestStrategy
{
    /**
     * @param Token $token
     * @return mixed
     */
    public function extractActualValue(Token $token);

    /**
     * @return mixed
     */
    public function getExpectedValue();
}
