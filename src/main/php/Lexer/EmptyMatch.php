<?php namespace Helstern\Nomsky\Lexer;

class EmptyMatch implements TokenMatch
{
    /**
     * @var EmptyMatch
     */
    private static $instance;

    /**
     * @return EmptyMatch
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getText()
    {
        return '';
    }

    public function getCharLength()
    {
        return 0;
    }

    public function getByteLength()
    {
        return 0;
    }
}
