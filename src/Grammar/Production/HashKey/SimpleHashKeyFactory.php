<?php namespace Helstern\Nomsky\Grammar\Production\HashKey;

use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\Predicate\AnySymbolPredicate;

class SimpleHashKeyFactory implements HashKeyFactory
{
    /**
     * @param Production $production
     * @return HashKey|SimpleHashKey
     */
    public function hash(Production $production)
    {
        $predicate = AnySymbolPredicate::singletonInstance();
        $symbols = $production->findAll($predicate);

        $hash = '';
        foreach ($symbols as $symbol) {
            $hash .= $symbol->getType() . $symbol->hashCode();
        }

        $hash = md5($hash);
        return new SimpleHashKey($hash);
    }
}
