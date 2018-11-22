<?php

namespace Noxyra\BasicSpinner\Spinner;

abstract class Spinner
{
    private $arrayMicroMasterSpin;

    public function __construct($arrayMicroMasterSpin = [])
    {
        /* The array format : [
            'animal|pet',                   // Required
            'Hi !|Key !|Good morning.|',    // or empty possibility
        ] */
        $this->arrayMicroMasterSpin = $arrayMicroMasterSpin;
    }

    /**
     * Main function for build the text
     *
     * @param string $text
     * @param array $replaceVarsArray
     * @param bool $autoInject
     * @return string
     */
    public function generateText(string $text, array $replaceVarsArray = [], bool $autoInject = true)
    {
        if ($autoInject) {
            $text = $this->autoInjectMicroMasterSpins($text);
        }

        if (!empty($replaceVarsArray)) {
            $text = self::replaceVars($text, $replaceVarsArray);
        }

        return self::execute($text);
    }

    /**
     * Execute the masterspin
     *
     * @param string $masterSpin
     * @return string
     */
    public static function execute(string $masterSpin)
    {
        $pattern = '#\{([^{}]*)\}#msi';
        $test = preg_match_all($pattern, $masterSpin, $out);

        if (!$test) {
            return $masterSpin;
        }

        $toFind = array();
        $toReplace = array();

        foreach ($out[0] as $id => $match) {
            $select = explode("|", $out[1][$id]);
            $toFind[] = $match;
            $toReplace[] = $select[rand(0, count($select) - 1)];
        }

        $response = str_replace($toFind, $toReplace, $masterSpin);

        return self::execute($response);

    }

    /**
     * Replace words by vars specified in $vars
     *
     * @param string $text
     * @param array $vars
     * @return string
     */
    public static function replaceVars(string $text, $vars = [])
    {
        foreach ($vars as $varName => $varValue) {
            $text = str_replace('$' . $varName . '$', $varValue, $text);
        }

        return $text;
    }

    /**
     * Use the array gived in constructor for replace specifics words by masterspin expressions
     *
     * @param string $text
     * @return mixed|string
     */
    public function autoInjectMicroMasterSpins(string $text)
    {
        foreach ($this->arrayMicroMasterSpin as $matchString) {
            $matchArray = explode('|', $matchString);
            foreach ($matchArray as $matchWord) {
                $old = $text;

                $text = str_replace(" " . $matchWord . " ", ' {' . $matchString . '} ', $text);

                if ($text !== $old) {
                    break;
                }
            }

        }

        return $text;
    }
}
