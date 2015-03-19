<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class StringLiteralNode extends AbstractEbnfNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /**
     * @param TextPosition $textPosition
     * @param string $rawString
     */
    public function __construct(TextPosition $textPosition, $rawString)
    {
        $this->textPosition = $textPosition;
        $this->string = $rawString;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
