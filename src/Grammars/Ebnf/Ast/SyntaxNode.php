<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class SyntaxNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var array | ProductionNode[] */
    protected $productionNodes;

    /** @var StringLiteralNode|null */
    protected $grammarTitle;

    /** @var StringLiteralNode|null */
    protected $grammarComment;

    public function __construct(
        TextPosition $textPosition,
        array $productionNodes,
        StringLiteralNode $grammarTitle = null,
        StringLiteralNode $grammarComment = null
    ) {
        $this->textPosition = $textPosition;
        $this->productionNodes = $productionNodes;
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
     * @return array|ProductionNode[]
     */
    public function getProductionNodes()
    {
        return $this->productionNodes;
    }

    public function getChildren()
    {
        if (is_null($this->grammarTitle)) {
            $children = [];
        } else {
            $children = [$this->grammarTitle];
        }

        $children = array_merge($children, $this->productionNodes);

        if (is_null($this->grammarComment)) {
            return $children;
        }

        $children[] = $this->grammarComment;
        return $children;
    }

    /**
     * @return TextPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
