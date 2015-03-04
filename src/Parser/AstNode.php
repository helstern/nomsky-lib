<?php namespace Helstern\Nomsky\Parser;

use Helstern\Nomsky\Text\TextPosition;

interface AstNode
{
    /**
     * @return TextPosition
     */
    public function getTextPosition();
}
