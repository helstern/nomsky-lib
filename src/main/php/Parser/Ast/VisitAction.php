<?php namespace Helstern\Nomsky\Parser\Ast;

interface VisitAction
{
    /**
     * @return AstNodeVisitor $visitor
     */
    public function getVisitor();

    /**
     * @return AstNode
     */
    public function getVisitReceiver();

    /**
     * @return bool
     */
    public function execute();
}
