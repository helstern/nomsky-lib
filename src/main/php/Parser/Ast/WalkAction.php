<?php namespace Helstern\Nomsky\Parser\Ast;

interface WalkAction
{
    /**
     * @return AstNode
     */
    public function getSubject();

    /**
     * @return bool
     */
    public function execute();
}
