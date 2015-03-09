<?php namespace Helstern\Nomsky\Parser\Ast;

use Helstern\Nomsky\Text\TextPosition;

interface AstNode
{
    /**
     * @return TextPosition
     */
    public function getTextPosition();
}
