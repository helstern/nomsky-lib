<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstNodeWalkStrategy
{
    /**
     *
     * @param AstNode $parent
     * @param WalkerStateMachine $walkState
     * @param WalkListItemCollector $walkListCollector
     *
     * @return int the new walker state
     */
    public function calculateWalkList(
        AstNode $parent,
        WalkListItemCollector $walkListCollector,
        WalkerStateMachine $walkState
    );
}
