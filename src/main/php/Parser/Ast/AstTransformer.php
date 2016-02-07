<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstTransformed
{
    /**
     * @param AstNode $astNode
     * @return AstNode the transformed tree
     */
    public function transform(AstNode $astNode);
}
