<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\AlternativeNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\CommentNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepeatedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SpecialSequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\StringLiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\NodeCounter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitorCollaborators;
use Helstern\Nomsky\Graphviz\DotFile;
use Helstern\Nomsky\Graphviz\DotWriter;
use Helstern\Nomsky\Parser\AstNodeVisitStrategy\AstNodeVisitorProvider;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingProvider;

class Visitors extends AbstractDispatchingProvider implements AstNodeVisitorProvider
{
    /** @var DotFile */
    protected $dotFile;

    /** @var \SplStack */
    protected $traversalStack;

    /** @var NodeCounter */
    protected $nodeCounter;

    /**
     * @param DotFile $dotFile
     * @param \SplStack $traversalStack
     */
    public function __construct(DotFile $dotFile, \SplStack $traversalStack = null)
    {
        $this->dotFile = $dotFile;
        if (is_null($traversalStack)) {
            $this->traversalStack = new \SplStack();
        } else {
            $this->traversalStack = $traversalStack;
        }

        $this->nodeCounter = new NodeCounter();
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
     * @return VisitorCollaborators
     */
    protected function createVisitorCollaborators()
    {
        $collaborators = new VisitorCollaborators($this->dotFile, $this->traversalStack, $this->nodeCounter);

        return $collaborators;
    }

    /**
     * @param AlternativeNode $node
     * @return AlternativeNodeVisitor
     */
    public function getAlternativeNodeVisitor(AlternativeNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new AlternativeNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param CommentNode $node
     * @return CommentNodeVisitor
     */
    public function getCommentNodeVisitor(CommentNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new CommentNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param GroupedExpressionNode $node
     * @return GroupedExpressionNodeVisitor
     */
    public function getGroupedExpressionNodeVisitor(GroupedExpressionNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new GroupedExpressionNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param IdentifierNode $node
     * @return IdentifierNodeVisitor
     */
    public function getIdentifierNodeVisitor(IdentifierNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new IdentifierNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param OptionalExpressionNode $node
     * @return OptionalExpressionNodeVisitor
     */
    public function getOptionalExpressionNodeVisitor(OptionalExpressionNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new OptionalExpressionNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param RepeatedExpressionNode $node
     * @return RepeatedExpressionNodeVisitor
     */
    public function getRepeatedExpressionNodeVisitor(RepeatedExpressionNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new RepeatedExpressionNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param RuleNode $node
     * @return RuleNodeVisitor
     */
    public function getRuleNodeVisitor(RuleNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new RuleNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param SequenceNode $node
     * @return SequenceNodeVisitor
     */
    public function getSequenceNodeVisitor(SequenceNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new SequenceNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param SpecialSequenceNode $node
     * @return SpecialSequenceNodeVisitor
     */
    public function getSpecialSequenceNodeVisitor(SpecialSequenceNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new SpecialSequenceNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param StringLiteralNode $node
     * @return StringLiteralNodeVisitor
     */
    public function getStringLiteralNodeVisitor(StringLiteralNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new StringLiteralNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }

    /**
     * @param SyntaxNode $node
     * @return SyntaxNodeVisitor
     */
    public function getSyntaxNodeVisitor(SyntaxNode $node)
    {
        $collaborators = $this->createVisitorCollaborators();
        $visitDispatcher = $this->createVisitDispatcher($node);

        $visitor = new SyntaxNodeVisitor($collaborators, $visitDispatcher);

        return $visitor;
    }
}
