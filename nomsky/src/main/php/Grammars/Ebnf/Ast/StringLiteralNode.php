<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\TokenPosition;

class StringLiteralNode extends AbstractEbnfNode implements AstNode
{
    /** @var TokenPosition */
    protected $textPosition;

    /**
     * @param TokenPosition $textPosition
     * @param string $rawString
     */
    public function __construct(TokenPosition $textPosition, $rawString)
    {
        $this->textPosition = $textPosition;
        $this->string = $rawString;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
