<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

interface AstNode
{
    /**
     * @return CharacterPosition
     */
    public function getTextPosition();
}
