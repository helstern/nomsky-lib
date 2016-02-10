<?php namespace Helstern\Nomsky\Tokens;

interface TokenEqualityTestStrategy
{
    /**
     * @param StringToken $token
     *
*@return mixed
     */
    public function extractActualValue(StringToken $token);

    /**
     * @return mixed
     */
    public function getExpectedValue();
}
