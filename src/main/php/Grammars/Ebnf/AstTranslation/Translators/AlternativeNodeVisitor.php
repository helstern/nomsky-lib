<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammars\Ebnf\Ast\AlternativeNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;

class AlternativeNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @param VisitContext $visitContext
     *
     */
    public function __construct(VisitContext $visitContext)
    {
        $this->visitContext = $visitContext;
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
        $expression = new Alternative(array_shift($children), $children);
        $this->visitContext->pushExpression($expression);
    }
}
