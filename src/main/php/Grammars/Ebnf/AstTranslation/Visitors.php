<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation;

use Helstern\Nomsky\Grammars\Ebnf\Ast\AlternativeNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\StringLiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators\AlternativeNodeVisitor;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators\GroupedExpressionNodeVisitor;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators\OptionalExpressionNodeVisitor;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators\RuleNodeVisitor;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators\SequenceNodeVisitor;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators\StringLiteralNodeVisitor;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitorProvider;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingProvider;

class Visitors extends AbstractDispatchingProvider implements AstNodeVisitorProvider
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @return Visitors
     */
    public static function defaultInstance()
    {
        $visitContext = new VisitContext();
        return new self($visitContext);
    }

    public function __construct(VisitContext $visitContext)
    {
        $this->visitContext = $visitContext;
    }

    /**
     * @param AlternativeNode $node
     * @return AlternativeNodeVisitor
     */
    public function getAlternativeNodeVisitor(AlternativeNode $node)
    {
        $visitDispatcher = $this->createVisitDispatcher($node);
        $visitor = new AlternativeNodeVisitor($this->visitContext, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param GroupedExpressionNode $node
     * @return GroupedExpressionNodeVisitor
     */
    public function getGroupedExpressionNodeVisitor(GroupedExpressionNode $node)
    {
        $visitDispatcher = $this->createVisitDispatcher($node);
        $visitor = new GroupedExpressionNodeVisitor($this->visitContext, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param OptionalExpressionNode $node
     * @return OptionalExpressionNodeVisitor
     */
    public function getOptionalExpressionNodeVisitor(OptionalExpressionNode $node)
    {
        $visitDispatcher = $this->createVisitDispatcher($node);
        $visitor = new OptionalExpressionNodeVisitor($this->visitContext, $visitDispatcher);

        return $visitor;
    }

//    /**
//     * @param RepeatedExpressionNode $node
//     * @return RepeatedExpressionNodeVisitor
//     */
//    public function getRepeatedExpressionNodeVisitor(RepeatedExpressionNode $node)
//    {
//        $collaborators = $this->createVisitorCollaborators();
//        $visitDispatcher = $this->createVisitDispatcher($node);
//
//        $visitor = new RepeatedExpressionNodeVisitor($collaborators, $visitDispatcher);
//
//        return $visitor;
//    }

    /**
     * @param RuleNode $node
     * @return RuleNodeVisitor
     */
    public function getRuleNodeVisitor(RuleNode $node)
    {
        $visitDispatcher = $this->createVisitDispatcher($node);
        $visitor = new RuleNodeVisitor($this->visitContext, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param SequenceNode $node
     * @return SequenceNodeVisitor
     */
    public function getSequenceNodeVisitor(SequenceNode $node)
    {
        $visitDispatcher = $this->createVisitDispatcher($node);
        $visitor = new SequenceNodeVisitor($this->visitContext, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param StringLiteralNode $node
     * @return StringLiteralNodeVisitor
     */
    public function getStringLiteralNodeVisitor(StringLiteralNode $node)
    {
        $visitDispatcher = $this->createVisitDispatcher($node);
        $visitor = new StringLiteralNodeVisitor($this->visitContext, $visitDispatcher);

        return $visitor;
    }

//    /**
//     * @param SyntaxNode $node
//     * @return SyntaxNodeVisitor
//     */
//    public function getSyntaxNodeVisitor(SyntaxNode $node)
//    {
//        $collaborators = $this->createVisitorCollaborators();
//        $visitDispatcher = $this->createVisitDispatcher($node);
//
//        $visitor = new SyntaxNodeVisitor($collaborators, $visitDispatcher);
//
//        return $visitor;
//    }
}
