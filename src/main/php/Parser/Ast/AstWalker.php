<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstWalker
{
    /**
     * @param AstNode $astNode
     * @return boolean false when the walk ended pre-maturely
     */
    public function walk(AstNode $astNode);
}
