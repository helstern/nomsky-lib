<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstNodeVisitor
{
    /**
     * @param AstNode $astNode
     *
     * @return boolean
     */
    public function preVisit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     *
     * @return boolean
     */
    public function visit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     *
     * @return boolean
     */
    public function postVisit(AstNode $astNode);
}
