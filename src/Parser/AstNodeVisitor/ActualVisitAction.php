<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\VisitAction;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

class ActualVisitAction implements VisitAction
{
    /** @var AstNode */
    protected $visitSubject;

    /**
     * @param AstNode $visitSubject
     */
    public function __construct(AstNode $visitSubject)
    {
        $this->visitSubject = $visitSubject;
    }

    /**
     * @return AstNode
     */
    public function getVisitReceiver()
    {
        return $this->visitSubject;
    }

    public function execute(AstNodeVisitor $visitor)
    {
        $visitor->visit($this->visitSubject);
    }
}

