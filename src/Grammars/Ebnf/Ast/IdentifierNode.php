<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class IdentifierNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var string */
    protected $name;

    public function __construct(TextPosition $textPosition, $name)
    {
        $this->textPosition = $textPosition;
        $this->name = $name;
    }

    /**
     * @return TextPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
