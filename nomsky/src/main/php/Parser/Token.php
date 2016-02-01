<?php namespace Helstern\Nomsky\Parser;

interface Token
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return TokenPosition
     */
    public function getPosition();
}
