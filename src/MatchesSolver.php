<?php

namespace Triun\LongestCommonSubstring;

/**
 * Return an array with all longest common strings.
 *
 * Class MatchesSolver
 *
 * @package Triun\LongestCommonSubstring
 */
class MatchesSolver extends Solver
{
    /**
     * @param array $matrix
     * @param int   $i
     * @param int   $j
     *
     * @return Match
     */
    protected function newIndex(array $matrix, int $i, int $j)
    {
        return new Match(
            [
                $i - $matrix[$i][$j] + 1,
                $j - $matrix[$i][$j] + 1,
            ],
            $matrix[$i][$j]
        );
    }

    /**
     * @param Match[] $longestIndexes
     * @param int     $longestLength
     * @param string  $stringA
     * @param array   $matrix
     * @param bool    $string
     *
     * @return object[] the extracted part of string or false on failure.
     */
    protected function result(
        array $longestIndexes,
        int $longestLength,
        array $tokensA,
        array $matrix,
        bool $string
    ) {
        if ($string) {
            return array_map(function (Match $result) use ($tokensA) {
                $result->value = implode('', array_slice($tokensA, $result->index(), $result->length));
                return $result;
            }, $longestIndexes);
        } else {
            return array_map(function (Match $result) use ($tokensA) {
                $result->value = array_slice($tokensA, $result->index(), $result->length);
                return $result;
            }, $longestIndexes);
        }
    }

    /**
     * @param string|string[] $stringA
     * @param string|string[] $stringB
     * @param bool            $string
     *
     * @return Matches
     */
    public function solve($stringA, $stringB, $string = true)
    {
        $nbArgs = func_num_args();
        if ($nbArgs > 2 && is_bool(func_get_arg($nbArgs-1))) {
            $rstring = func_get_arg($nbArgs-1);
            $nbArgs--;
        } else {
            $rstring = true;
        }
        if ($nbArgs > 2) {
            // TODO: Get the best combination, not just the first one.
            $arguments = func_get_args();
            array_splice($arguments, 0, 2, [(string)$this->solve($stringA, $stringB, $rstring)]);

            return call_user_func_array([$this, 'solve'], $arguments);
        }

        return new Matches(parent::solve($stringA, $stringB, $rstring));
    }
}
