<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNode;

class NonDispatchableVisitException extends \BadMethodCallException
{
    /** @var AstNode */
    protected $visitSubject;

    public function __construct(AstNode $visitSubject, $message = "", $code = 0)
    {
        parent::__construct($message, $code);
        $this->visitSubject = $visitSubject;
    }

    /**
     * @return AstNode
     */
    public function getVisitSubject()
    {
        return $this->visitSubject;
    }

}
