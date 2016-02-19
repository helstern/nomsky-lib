<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors;

use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\AstTranslatorContext;

class GroupNodeVisitor
{
    /**
     * @var AstTranslatorContext
     */
    private $visitContext;

    /**
     * @param AstTranslatorContext $visitContext
     *
     */
    public function __construct(AstTranslatorContext $visitContext)
    {
        $this->visitContext = $visitContext;
    }

    /**
     * @param GroupNode $astNode
     *
     * @return bool
     */
    public function preVisitGroupNode(GroupNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param GroupNode $astNode
     *
     * @return bool
     */
    public function visitGroupNode(GroupNode $astNode)
    {}

    /**
     * @param GroupNode $astNode
     *
     * @return bool
     */
    public function postVisitGroupNode(GroupNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Group($child);
        $this->visitContext->pushExpression($expression);
    }
}
