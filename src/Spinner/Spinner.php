<?php

namespace Noxyra\BasicSpinner\Spinner;

abstract class Spinner
{
    protected const API_ENDPOINT = "https://api.datamuse.com/words?";

    /**
     * Execute the spin
     *
     * @param string $text
     * @return string
     */
    public static function execute(string $text)
    {
        $pattern = '#\{([^{}]*)\}#msi';
        $test = preg_match_all($pattern, $text, $out);
        if(!$test)
            return $text;

        $toFind = array();
        $toReplace = array();

        foreach($out[0] as $id => $match)
        {
            $select = explode("|", $out[1][$id]);
            $toFind[] = $match;
            $toReplace[] = $select[rand(0, count($select)-1)];
        }
        $response = str_replace($toFind, $toReplace, $text);

        return self::execute($response);

    }
}
