<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\Ast\WalkAction;

interface VisitAction extends WalkAction
{
    /**
     * @return AstNodeVisitor $visitor
     */
    public function getVisitor();
}
