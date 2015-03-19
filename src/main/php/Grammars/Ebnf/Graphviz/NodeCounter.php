<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz;

use Helstern\Nomsky\Parser\Ast\AstNode;

class NodeCounter
{
    protected $totalCount = 0;

    /**
     * @return int
     */
    public function getNodeCount()
    {
        return $this->totalCount;
    }

    /**
     * @param AstNode $astNode
     *
     * @return NodeCounter
     */
    public function increment(AstNode $astNode)
    {
        $this->totalCount++;

        return $this;
    }
}
