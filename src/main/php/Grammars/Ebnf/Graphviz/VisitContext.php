<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz;

use Helstern\Nomsky\Parser\Ast\AstNode;

class VisitContext
{
    /** @var \SplStack */
    private $traversalStack;

    private $totalCount = 0;

    public function __construct()
    {
        $this->traversalStack = new \SplStack();
    }

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
     * @return VisitContext
     */
    public function incrementNodeCount(AstNode $astNode)
    {
        $this->totalCount++;
        return $this;
    }

    /**
     * @return mixed
     */
    public function peekParentId()
    {
        return $this->traversalStack->top();
    }

    /**
     * @return int
     */
    public function countParentIds()
    {
        return $this->traversalStack->count();
    }

    public function popParentId()
    {
        $this->traversalStack->pop();
    }

    /**
     * @param mixed $id
     */
    public function pushParentId($id)
    {
        $this->traversalStack->push($id);
    }
}
