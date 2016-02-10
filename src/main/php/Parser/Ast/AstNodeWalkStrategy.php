<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstNodeWalkStrategy
{
    /**
     * @param AstNode $parent
     * @return \Traversable
     */
    public function calculateWalkList(AstNode $parent);
}
