<?php namespace Helstern\Nomsky\Parser\Ast;

class WalkerStateMachine
{
    const STATE_IGNORE = 2;

    const STATE_WALK = 4;

    private $state;

    public function __construct()
    {
        $this->state = WalkerStateMachine::STATE_WALK;
    }

    /**
     * @param AstNode $node
     *
     * @return WalkerStateMachine
     */
    public function ignore(AstNode $node)
    {
        $this->state = WalkerStateMachine::STATE_IGNORE;
        return $this;
    }

    /**
     * @param AstNode $node
     *
     * @return WalkerStateMachine
     */
    public function walk(AstNode $node)
    {
        $this->state = WalkerStateMachine::STATE_WALK;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }
}
