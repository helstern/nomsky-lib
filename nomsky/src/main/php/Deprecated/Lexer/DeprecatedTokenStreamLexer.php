<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Parser\Lexer;
use Helstern\Nomsky\Tokens\StringToken;
use Helstern\Nomsky\Tokens\DeprecatedTokenDefinition;

class DeprecatedTokenStreamLexer implements Lexer
{
    /** @var DeprecatedTokenStream */
    protected $tokenStream;

    /** @var StringToken */
    protected $token;

    /** @var DeprecatedTokenDefinition */
    protected $eofTokenDefinition;

    /**
     * @param DeprecatedTokenStream $tokenStream
     * @param DeprecatedTokenDefinition $eofDefinition
     */
    public function __construct(DeprecatedTokenStream $tokenStream, DeprecatedTokenDefinition $eofDefinition)
    {
        $this->tokenStream = $tokenStream;
        $this->token = $tokenStream->current();
        $tokenStream->next();

        $this->eofTokenDefinition = $eofDefinition;
    }

    public function currentToken()
    {
        return $this->token;
    }

    public function nextToken()
    {
        if ($this->token->getType() == $this->eofTokenDefinition->getType()) {
            return false;
        }

        $this->token = $this->tokenStream->current();
        $this->tokenStream->next();
        return true;
    }

    /**
     * @return StringToken
     * @throws \RuntimeException
     */
    public function peekToken()
    {
        if ($this->token->getType() == $this->eofTokenDefinition->getType()) {
            throw new \RuntimeException('Can not peek over EOF');
        }

        return $this->tokenStream->current();
    }
}
