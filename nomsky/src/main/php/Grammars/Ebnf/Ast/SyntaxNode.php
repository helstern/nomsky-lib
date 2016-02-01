<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\TokenPosition;

class SyntaxNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
{
    /** @var TokenPosition */
    protected $textPosition;

    /** @var array | RuleNode[] */
    protected $ruleNodes;

    /** @var StringLiteralNode|null */
    protected $grammarTitle;

    /** @var StringLiteralNode|null */
    protected $grammarComment;

    public function __construct(
        TokenPosition $textPosition,
        array $productionNodes,
        StringLiteralNode $grammarTitle = null,
        StringLiteralNode $grammarComment = null
    ) {
        $this->textPosition = $textPosition;
        $this->ruleNodes = $productionNodes;
        $this->grammarTitle = $grammarTitle;
        $this->grammarComment = $grammarComment;
    }

    /**
     * @return StringLiteralNode|null
     */
    public function getGrammarTitleNode()
    {
        return $this->grammarTitle;
    }

    /**
     * @return StringLiteralNode|null
     */
    public function getGrammarCommentNode()
    {
        return $this->grammarComment;
    }

    /**
     * @return array|RuleNode[]
     */
    public function getRuleNodes()
    {
        return $this->ruleNodes;
    }

    public function getChildren()
    {
        if (is_null($this->grammarTitle)) {
            $children = [];
        } else {
            $children = [$this->grammarTitle];
        }

        $children = array_merge($children, $this->ruleNodes);

        if (is_null($this->grammarComment)) {
            return $children;
        }

        $children[] = $this->grammarComment;
        return $children;
    }

    /**
     * @return TokenPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }

    /**
     * @return int
     */
    public function countChildren()
    {
        $count = 1 + count($this->ruleNodes) + 1;

        if (is_null($this->grammarTitle)) {
            $count--;
        }

        if (is_null($this->grammarComment)) {
            $count--;
        }

        return $count;
    }
}
