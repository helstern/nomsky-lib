<?php namespace Helstern\Nomsky\Parser\Ast;

interface AstNodeVisitor
{
    public function preVisit(AstNode $astNode);

    public function visit(AstNode $astNode);

    public function postVisit(AstNode $astNode);
}
