<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

interface AstNodeVisitorProvider
{
    /**
     * @param AstNode $node
     * @return AstNodeVisitor
     */
    public function getVisitor(AstNode $node);
}
