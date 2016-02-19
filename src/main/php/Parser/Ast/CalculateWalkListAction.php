<?php namespace Helstern\Nomsky\Parser\Ast;

/**
 * TODO: This class should generate the walk list for $node
 */
class CalculateWalkListAction implements WalkAction
{
    /**
     * @var AstNode
     */
    private $node;

    public function __construct(AstNode $node)
    {
        $this->node = $node;
    }

    /**
     * @return AstNode
     */
    public function getSubject()
    {
        return $this->node;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        return false;
    }
}
