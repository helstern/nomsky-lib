<?php namespace Helstern\Nomsky\Grammar\Symbol;

class StandardSymbol implements Symbol
{
    /** @var int  */
    private $type;

    /** @var string */
    private $characters;

    /**
     * @param string $representation
     *
     * @return StandardSymbol
     */
    public static function nonTerminal($representation)
    {
        return new self(Symbol::TYPE_NON_TERMINAL, $representation);
    }

    /**
     * @param string $representation
     *
     * @return StandardSymbol
     */
    public static function terminal($representation)
    {
        return new self(Symbol::TYPE_TERMINAL, $representation);
    }

    /**
     * @param int $type
     * @param string $representation
     */
    public function __construct($type, $representation)
    {
        $this->type = $type;
        $this->characters = $representation;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->characters;
    }
}
