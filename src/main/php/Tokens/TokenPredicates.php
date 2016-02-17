<?php namespace Helstern\Nomsky\Tokens;

use Helstern\Nomsky\Parser\Token;

class TokenPredicates
{
    /**
     * @param Token $token
     * @param int $type
     *
     * @return bool
     */
    public function hasSameType(Token $token, $type)
    {
        return $token->getType() === $type;
    }

    /**
     * @param Token $token
     * @param array $typeList
     *
     * @return bool
     */
    public function hasAnyType(Token $token, array $typeList)
    {
        do {
            $hasAnyType = $this->hasSameType($token, current($typeList));
            next($typeList);
        } while(key($typeList) && !$hasAnyType);

        return $hasAnyType;
    }

    /**
     * @param Token $token
     * @param int $type
     * @param string $value
     *
     * @return bool
     */
    public function hasSameTypeAndValue(Token $token, $type, $value)
    {
        return $token->getType() === $type && $token->getValue() === $value;
    }

    /**
     * @param Token $token
     * @param int $type
     * @param array $values
     *
     * @return bool
     */
    public function hasSameTypeAndAnyValue(Token $token, $type, array $values)
    {
        do {
            $hasSameTypeAndValue = $this->hasSameTypeAndValue($token, $type, current($values));
            next($values);
        } while(key($values) && !$hasSameTypeAndValue);

        return $hasSameTypeAndValue;
    }

}
