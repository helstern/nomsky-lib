<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class IdentifierNode implements AstNode
{
    /** @var CharacterPosition */
    protected $textPosition;

    /** @var string */
    protected $name;

    public function __construct(CharacterPosition $textPosition, $name)
    {
        $this->textPosition = $textPosition;
        $this->name = $name;
    }

    /**
     * @return CharacterPosition
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
