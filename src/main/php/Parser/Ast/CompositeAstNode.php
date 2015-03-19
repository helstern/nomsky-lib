<?php namespace Helstern\Nomsky\Parser\Ast;

interface CompositeAstNode extends AstNode
{
    /**
     * Returns the ordered list of this node's children
     *
     * @return AstNode[]
     */
    public function getChildren();

    /**
     * @return int
     */
    public function countChildren();
}
