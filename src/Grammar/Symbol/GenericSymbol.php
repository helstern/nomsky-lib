<?php namespace Helstern\Nomsky\Grammar\Symbol;

class GenericSymbol implements Symbol
{
    /** @var int  */
    protected $type;

    /** @var string */
    protected $characters;

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
        return Symbol::TYPE_TERMINAL;
    }

    /**
     * @return string
     */
    public function hashCode()
    {
        return $this->characters;
    }
}
