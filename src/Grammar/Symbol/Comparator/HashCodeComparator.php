<?php namespace Helstern\Nomsky\Grammar\Symbol\Comparator;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class HashCodeComparator
{
    /** @var HashCodeComparator */
    static private $singletonInstance;

    /**
     * @return HashCodeComparator
     */
    static public function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

    public function compare(Symbol $leftSymbol, Symbol $rightSymbol)
    {
        $leftString = $leftSymbol->hashCode();
        $rightString = $rightSymbol->hashCode();

        return strcmp($leftString, $rightString);
    }

    /**
     * @param array|Symbol[] $listOfSymbols
     * @return array|Symbol[]
     */
    public function unique(array $listOfSymbols)
    {
        usort($listOfSymbols, $this->toClosure());

        $previousTerminal = array_shift($listOfSymbols);
        $uniqueItems[] = $previousTerminal;

        $nrSorted = count($listOfSymbols);
        while ($nrSorted > 0) {
            $nextTerminal = array_shift($listOfSymbols);
            $nrSorted--;

            if (0 !== $this->compare($previousTerminal, $nextTerminal)) {
                $uniqueItems[] = $nextTerminal;
                $previousTerminal = $nextTerminal;
            }
        }

        return $uniqueItems;
    }

    /**
     * @return callable|\Closure
     */
    public function toClosure()
    {
        $me = $this;
        return function (Symbol $left, Symbol $right) use ($me) {
            return $me->compare($left, $right);
        };
    }
}
