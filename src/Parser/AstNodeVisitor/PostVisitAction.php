<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\VisitAction;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

class PostVisitAction implements VisitAction
{
    /** @var AstNode */
    protected $visitReceiver;

    /** @var AstNodeVisitor */
    protected $visitor;

    /**
     * @param AstNode $visitReceiver
     * @param AstNodeVisitor $visitor
     */
    public function __construct(AstNode $visitReceiver, AstNodeVisitor $visitor)
    {
        $this->visitReceiver = $visitReceiver;
        $this->visitor = $visitor;
    }

    /**
     * @return AstNodeVisitor $visitor
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @return AstNode
     */
    public function getVisitReceiver()
    {
        return $this->visitReceiver;
    }

    public function execute()
    {
        $this->visitor->visit($this->visitReceiver);
        return true;
    }


}

