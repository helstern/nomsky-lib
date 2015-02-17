<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\Text\TextPosition;

interface AstNode
{
    /**
     * @return TextPosition
     */
    public function getTextPosition();
}
