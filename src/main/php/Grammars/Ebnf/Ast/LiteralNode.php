<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\CharPosition;

class LiteralNode extends AbstractEbnfNode implements AstNode
{
    /** @var CharPosition */
    protected $textPosition;

    /**
     * @param CharPosition $textPosition
     * @param string $rawString
     */
    public function __construct(CharPosition $textPosition, $rawString)
    {
        $this->textPosition = $textPosition;
        $this->string = $rawString;
    }

    /**
     * @return string
     */
    public function getLiteral()
    {
        return $this->string;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
