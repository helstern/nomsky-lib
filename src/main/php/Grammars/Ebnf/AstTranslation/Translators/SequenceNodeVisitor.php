<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammars\Ebnf\Ast\AlternativeNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class SequenceNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /** @var VisitDispatcher  */
    protected $visitDispatcher;

    /**
     * @param VisitContext $visitContext
     * @param VisitDispatcher $visitDispatcher
     *
     */
    public function __construct(VisitContext $visitContext, VisitDispatcher $visitDispatcher)
    {
        $this->visitContext = $visitContext;
        $this->visitDispatcher = $visitDispatcher;
    }

    /**
     * @return VisitDispatcher
     */
    protected function getVisitDispatcher()
    {
        $visitDispatcher = $this->visitDispatcher;
        return $visitDispatcher;
    }

    /**
     * @param AlternativeNode $astNode
     * @return bool
     */
    public function preVisitAlternativeNode(AlternativeNode $astNode)
    {
        $this->visitContext->pushMarker($this);
        return true;
    }

    /**
     * @param AlternativeNode $astNode
     * @return bool
     */
    public function visitAlternativeNode(AlternativeNode $astNode)
    {
        return true;
    }

    /**
     * @param AlternativeNode $astNode
     * @return bool
     */
    public function postVisitAlternativeNode(AlternativeNode $astNode)
    {
        $children = $this->visitContext->popExpressions($this);
        $expression = new Sequence(array_shift($children), $children);
        $this->visitContext->pushExpression($expression);
    }


}
