<?php namespace Helstern\Nomsky\Parsers\EbnfAst;

use Helstern\Nomsky\Parsers\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class SyntaxNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var array | ProductionNode[] */
    protected $productionNodes;

    /** @var TextPosition */
    protected $textPosition;

    public function __construct(TextPosition $textPosition, array $productionNodes, $grammarTitle, $comment)
    {
        $this->textPosition = $textPosition;
        $this->productionNodes = $productionNodes;
        $this->grammarTitle = $grammarTitle;
        $this->comment = $comment;
    }

    public function getChildren()
    {
        return $this->productionNodes;
    }

    /**
     * @return TextPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
