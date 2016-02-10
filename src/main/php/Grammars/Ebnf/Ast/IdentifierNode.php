<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\CharPosition;

class IdentifierNode extends AbstractEbnfNode implements AstNode
{
    /** @var CharPosition */
    protected $textPosition;

    /** @var string */
    protected $name;

    public function __construct(CharPosition $textPosition, $identifierName)
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
     * @return CharPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
