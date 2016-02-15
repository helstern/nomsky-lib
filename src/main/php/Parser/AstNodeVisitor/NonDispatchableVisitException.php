<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNode;

class NonDispatchableVisitException extends \BadMethodCallException
{
    /** @var AstNode */
    protected $visitSubject;

    /**
     * @param AstNode $visitSubject
     * @param string $message
     * @param int $code
     */
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
