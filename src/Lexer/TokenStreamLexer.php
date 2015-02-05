<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Parser\Lexer;
use Helstern\Nomsky\Tokens\Token;
use Helstern\Nomsky\Tokens\TokenStream\TokenStream;
use Helstern\Nomsky\Tokens\TokenTypeEnum;

class TokenStreamLexer implements Lexer
{
    /** @var TokenStream */
    protected $tokenStream;

    /** @var Token */
    protected $token;

    /**
     * @param TokenStream $tokenStream
     */
    public function __construct(TokenStream $tokenStream)
    {
        $this->tokenStream = $tokenStream;
        $this->token = $tokenStream->current();
        $tokenStream->next();
    }

    public function currentToken()
    {
        return $this->token;
    }

    public function nextToken()
    {
        $previousToken = $this->token;
        if ($previousToken->getType() === TokenTypeEnum::TYPE_EOF) {
            return false;
        }

        $this->token = $this->tokenStream->current();
        $this->tokenStream->next();

        return true;
    }

    public function peekToken()
    {
        if ($this->token->getType() === TokenTypeEnum::TYPE_EOF) {
            throw new \RuntimeException('Can not peek over EOF');
        }

        return $this->tokenStream->current();
    }
}
