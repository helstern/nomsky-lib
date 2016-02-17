<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammars\Ebnf\Ast\AlternativeNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepeatedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\StringLiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingVisitorBuilder;

class Translators
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @return Translators
     */
    public static function defaultInstance()
    {
        $visitContext = new VisitContext();
        return new self($visitContext);
    }

    /**
     * @param VisitContext $visitContext
     */
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
        $visitor = new AlternativeNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param GroupedExpressionNode $node
     * @return GroupedExpressionNodeVisitor
     */
    public function getGroupedExpressionNodeVisitor(GroupedExpressionNode $node)
    {
        $visitor = new GroupedExpressionNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param IdentifierNode $node
     * @return IdentifierNodeVisitor
     */
    public function getIdentifierNodeVisitor(IdentifierNode $node)
    {
        $visitor = new IdentifierNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param OptionalExpressionNode $node
     * @return OptionalExpressionNodeVisitor
     */
    public function getOptionalExpressionNodeVisitor(OptionalExpressionNode $node)
    {
        $visitor = new OptionalExpressionNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param RepeatedExpressionNode $node
     * @return RepetitionVisitor
     */
    public function getRepeatedExpressionNodeVisitor(RepeatedExpressionNode $node)
    {
        $visitor = new RepetitionVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param RuleNode $node
     * @return RuleNodeVisitor
     */
    public function getRuleNodeVisitor(RuleNode $node)
    {
        $visitor = new RuleNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param SequenceNode $node
     * @return SequenceNodeVisitor
     */
    public function getSequenceNodeVisitor(SequenceNode $node)
    {
        $visitor = new SequenceNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param StringLiteralNode $node
     * @return StringLiteralNodeVisitor
     */
    public function getStringLiteralNodeVisitor(StringLiteralNode $node)
    {
        $visitor = new StringLiteralNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
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

    /**
     * @param $visitor
     * @param AstNode $node
     *
     * @return DispatchingVisitor
     */
    private function createDispatchingVisitor($visitor, AstNode $node)
    {
        $builder = new DispatchingVisitorBuilder();
        $node->configureDoubleDispatcher($builder);
        $builder->setVisitor($visitor);
        $dispatcher = $builder->build();

        return $dispatcher;
    }
}
