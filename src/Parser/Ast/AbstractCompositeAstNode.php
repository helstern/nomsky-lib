<?php namespace Helstern\Nomsky\Parser\Ast;

abstract class AbstractCompositeAstNode
{
    public function collectChildrenInList(\SplDoublyLinkedList $list)
    {
        foreach ($this->getChildren() as $child) {
            $list->push($child);
        }
    }

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
