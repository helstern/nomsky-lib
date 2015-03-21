<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Parser\Lexer;
use Helstern\Nomsky\Tokens\Token;
use Helstern\Nomsky\Tokens\TokenDefinition;

class TokenStreamLexer implements Lexer
{
    /** @var TokenStream */
    protected $tokenStream;

    /** @var Token */
    protected $token;

    /** @var TokenDefinition */
    protected $eofTokenDefinition;

    /**
     * @param TokenStream $tokenStream
     * @param TokenDefinition $eofTokenEqualityTester
     */
    public function __construct(TokenStream $tokenStream, TokenDefinition $eofTokenEqualityTester)
    {
        $this->tokenStream = $tokenStream;
        $this->token = $tokenStream->current();
        $tokenStream->next();

        $this->eofTokenDefinition = $eofTokenEqualityTester;
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
     * @return Token
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
