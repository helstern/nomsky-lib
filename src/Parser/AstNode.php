<?php namespace Helstern\Nomsky\Parsers;

use Helstern\Nomsky\Text\TextPosition;

interface AstNode
{
    /**
     * @return TextPosition
     */
    public function getTextPosition();
}
