<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\ChoiceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\CommentNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepetitionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\ConcatenationNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SpecialSequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\LiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotFile;
use Helstern\Nomsky\Graphviz\DotWriter;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingVisitorBuilder;

class Writers
{
    /** @var DotFile */
    private $dotFile;
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @var DotWriter
     */
    private $dotWriter;

    /**
     * @var Formatter
     */
    private $formatter;


    /**
     * @param DotFile $dotFile
     * @param VisitContext $visitContext
     */
    public function __construct(DotFile $dotFile, VisitContext $visitContext)
    {
        $this->dotFile = $dotFile;
        $this->visitContext = $visitContext;
        $this->dotWriter = new DotWriter($dotFile);
        $this->formatter = new Formatter();
    }

    /**
     * @return DotWriter
     */
    public function createDotWriter()
    {
        $dotWriter = new DotWriter($this->dotFile);
        return $dotWriter;
    }

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

    /**
     * @param ChoiceNode $node
     *
*@return ChoiceNodeVisitor
     */
    public function getAlternativeNodeVisitor(ChoiceNode $node)
    {
        $visitor = new ChoiceNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);

        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param CommentNode $node
     * @return CommentNodeVisitor
     */
    public function getCommentNodeVisitor(CommentNode $node)
    {
        $visitor = new CommentNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param GroupNode $node
     *
*@return GroupNodeVisitor
     */
    public function getGroupedExpressionNodeVisitor(GroupNode $node)
    {
        $visitor = new GroupNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param IdentifierNode $node
     * @return IdentifierNodeVisitor
     */
    public function getIdentifierNodeVisitor(IdentifierNode $node)
    {
        $visitor = new IdentifierNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param OptionalNode $node
     *
*@return OptionalNodeVisitor
     */
    public function getOptionalExpressionNodeVisitor(OptionalNode $node)
    {
        $visitor = new OptionalNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param RepetitionNode $node
     *
*@return RepetitionNodeVisitor
     */
    public function getRepeatedExpressionNodeVisitor(RepetitionNode $node)
    {
        $visitor = new RepetitionNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param RuleNode $node
     * @return RuleNodeVisitor
     */
    public function getRuleNodeVisitor(RuleNode $node)
    {
        $visitor = new RuleNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param ConcatenationNode $node
     *
*@return ConcatenationNodeVisitor
     */
    public function getSequenceNodeVisitor(ConcatenationNode $node)
    {
        $visitor = new ConcatenationNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param SpecialSequenceNode $node
     * @return SpecialSequenceNodeVisitor
     */
    public function getSpecialSequenceNodeVisitor(SpecialSequenceNode $node)
    {
        $visitor = new SpecialSequenceNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param LiteralNode $node
     *
*@return LiteralNodeVisitor
     */
    public function getStringLiteralNodeVisitor(LiteralNode $node)
    {
        $visitor = new LiteralNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }

    /**
     * @param SyntaxNode $node
     * @return SyntaxNodeVisitor
     */
    public function getSyntaxNodeVisitor(SyntaxNode $node)
    {
        $visitor = new SyntaxNodeVisitor($this->visitContext, $this->dotWriter, $this->formatter);
        $dispatcher = $this->createDispatchingVisitor($visitor, $node);
        return $dispatcher;
    }
}
