<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\TokenPosition;

class IdentifierNode extends AbstractEbnfNode implements AstNode
{
    /** @var TokenPosition */
    protected $textPosition;

    /** @var string */
    protected $name;

    public function __construct(TokenPosition $textPosition, $identifierName)
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
     * @return TokenPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
