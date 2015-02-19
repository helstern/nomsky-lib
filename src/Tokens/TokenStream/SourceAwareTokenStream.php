<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Text\TextSource;
use Helstern\Nomsky\Lexer\TokenStream;

interface SourceAwareTokenStream extends TokenStream
{
    /**
     * @return TextSource
     */
    public function getSource();
}
