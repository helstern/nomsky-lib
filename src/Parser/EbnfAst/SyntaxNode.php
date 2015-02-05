<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class SyntaxNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var array | ProductionNode[] */
    protected $productionNodes;

    /** @var CharacterPosition */
    protected $textPosition;

    public function __construct(CharacterPosition $textPosition, array $productionNodes, $grammarTitle, $comment)
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
     * @return CharacterPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
