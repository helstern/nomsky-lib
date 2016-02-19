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

    public function getRawLiteral()
    {
        return $this->string;
    }

    /**
     * @return string
     */
    public function getLiteral()
    {
        $delimiter = $this->string[0];
        return trim($this->string, $delimiter);
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
