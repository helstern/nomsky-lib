<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

interface AstNodeVisitorProvider
{
    /**
     * Return the corresponding visitor for $node. if no suitable visitor exists, it returns null
     *
     * @param AstNode $node
     * @return AstNodeVisitor|null
     */
    public function getVisitor(AstNode $node);
}
