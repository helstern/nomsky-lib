<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstNodeVisitorProvider
{
    /**
     * @param AstNode $node
     * @return AstNodeVisitor
     */
    public function getVisitor(AstNode $node);
}
