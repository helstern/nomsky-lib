<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammars\Ebnf\Ast\ChoiceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepetitionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\ConcatenationNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\LiteralNode;
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
     * @param ChoiceNode $node
     *
     * @return ChoiceNodeVisitor
     */
    public function getChoiceNodeVisitor(ChoiceNode $node)
    {
        $visitor = new ChoiceNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param GroupNode $node
     *
     * @return GroupNodeVisitor
     */
    public function getGroupNodeVisitor(GroupNode $node)
    {
        $visitor = new GroupNodeVisitor($this->visitContext);
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
     * @param OptionalNode $node
     *
     * @return OptionalNodeVisitor
     */
    public function getOptionalNodeVisitor(OptionalNode $node)
    {
        $visitor = new OptionalNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param RepetitionNode $node
     *
     * @return RepetitionNodeVisitor
     */
    public function getRepetitionNodeVisitor(RepetitionNode $node)
    {
        $visitor = new RepetitionNodeVisitor($this->visitContext);
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
     * @param ConcatenationNode $node
     *
     * @return ConcatenationNodeVisitor
     */
    public function getConcatenationNodeVisitor(ConcatenationNode $node)
    {
        $visitor = new ConcatenationNodeVisitor($this->visitContext);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param LiteralNode $node
     *
     * @return LiteralNodeVisitor
     */
    public function getLiteralNodeVisitor(LiteralNode $node)
    {
        $visitor = new LiteralNodeVisitor($this->visitContext);
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
