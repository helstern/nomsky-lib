<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class IdentifierNode extends AbstractEbnfNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var string */
    protected $name;

    public function __construct(TextPosition $textPosition, $identifierName)
    {
        $this->textPosition = $textPosition;
        $this->name = $identifierName;
    }

    /**
     * @return string
     */
    public function getIdentifierName()
    {
        return $this->name;
    }

    /**
     * @return TextPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
