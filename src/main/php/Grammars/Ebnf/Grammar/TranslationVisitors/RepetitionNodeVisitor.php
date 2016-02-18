<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors;

use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepetitionNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\AstTranslatorContext;

class RepetitionNodeVisitor
{
    /**
     * @var AstTranslatorContext
     */
    private $visitContext;

    /**
     * @param AstTranslatorContext $visitContext
     */
    public function __construct(AstTranslatorContext $visitContext)
    {
        $this->visitContext = $visitContext;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function preVisitRepetitionNode(RepetitionNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function visitRepetitionNode(RepetitionNode $astNode)
    {
        return true;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function postVisitRepetitionNode(RepetitionNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Repetition($child);
        $this->visitContext->pushExpression($expression);

        return true;
    }
}
