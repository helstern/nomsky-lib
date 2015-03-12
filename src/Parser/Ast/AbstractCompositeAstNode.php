<?php namespace Helstern\Nomsky\Parser\Ast;

abstract class AbstractCompositeAstNode implements AstNode
{
    /**
     * Returns the ordered list of this node's children
     *
     * @return AstNode[]
     */
    abstract public function getChildren();

    /**
     * @return int
     */
    abstract public function countChildren();
}
