<?php namespace Helstern\Nomsky\Tokens;

class TokenPredicates
{
    /**
     * @param StringToken $token
     * @param int $type
     *
*@return bool
     */
    public function hasSameType(StringToken $token, $type)
    {
        return $token->getType() === $type;
    }

    /**
     * @param StringToken $token
     * @param array $typeList
     *
*@return bool
     */
    public function hasAnyType(StringToken $token, array $typeList)
    {
        do {
            $hasAnyType = $this->hasSameType($token, current($typeList));
            next($typeList);
        } while(key($typeList) && !$hasAnyType);

        return $hasAnyType;
    }

    /**
     * @param StringToken $token
     * @param int $type
     * @param string $value
     *
*@return bool
     */
    public function hasSameTypeAndValue(StringToken $token, $type, $value)
    {
        return $token->getType() === $type && $token->getValue() === $value;
    }

    /**
     * @param StringToken $token
     * @param int $type
     * @param array $values
     *
*@return bool
     */
    public function hasSameTypeAndAnyValue(StringToken $token, $type, array $values)
    {
        do {
            $hasSameTypeAndValue = $this->hasSameTypeAndValue($token, $type, current($values));
            next($values);
        } while(key($values) && !$hasSameTypeAndValue);

        return $hasSameTypeAndValue;
    }

}
