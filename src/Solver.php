<?php

namespace Triun\LongestCommonSubstring;

/**
 * Class Solver
 *
 * @package Triun\LongestCommonSubstring
 */
class Solver implements SolverInterface
{
    /**
     * @param array $matrix
     * @param int   $i
     * @param int   $j
     *
     * @return int
     */
    protected function newIndex(array $matrix, int $i, int $j)
    {
        return $i - $matrix[$i][$j] + 1;
    }

    /**
     * @param array    $longestIndexes
     * @param int      $longestLength
     * @param string[] $tokensA
     * @param int[][]  $matrix
     * @param bool     $string
     *
     * @return string|string[] the extracted part of string.
     */
    protected function result(
        array $longestIndexes,
        int $longestLength,
        array $tokensA,
        array $matrix,
        bool $string
    ) {
        if ($string) {
            return count($longestIndexes) === 0 ? '' : implode('', array_slice($tokensA, $longestIndexes[0], $longestLength));
        } else {
            return count($longestIndexes) === 0 ? [''] : array_slice($tokensA, $longestIndexes[0], $longestLength);
        }
    }

    /**
     * @param string|string[] $stringA
     * @param string|string[] $stringB
     * @param bool            $string
     *
     * @return string|string[]
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
            $arguments = func_get_args();
            array_splice($arguments, 0, 2, [$this->solve($stringA, $stringB, $rstring)]);

            return call_user_func_array([$this, 'solve'], $arguments);
        }

        if (is_string($stringA)) {
            $tokensA = [];
            for ($i=0; $i < max(mb_strlen($stringA), 1); $i++) {
                $tokensA[] = mb_substr($stringA, $i, 1);
            }
        } else {
            $tokensA = $stringA ?: [''];
            $j = 0;
            foreach ($tokensA as $i => $tokenA) {
                if ($i !== $j++ || !is_string($tokenA)) {
                    throw new \RuntimeException();
                }
            }
        }

        if (is_string($stringB)) {
            $tokensB = [];
            for ($i=0; $i < max(mb_strlen($stringB), 1); $i++) {
                $tokensB[] = mb_substr($stringB, $i, 1);
            }
        } else {
            $tokensB = $stringB ?: [''];
            $i = 0;
            foreach ($tokensB as $j => $tokenB) {
                if ($j !== $i++ || !is_string($tokenB)) {
                    throw new \RuntimeException();
                }
            }
        }

        $matrix = array_fill_keys(array_keys($tokensA), array_fill_keys(array_keys($tokensB), 0));
        $longestLength = 0;
        $longestIndexes = [];

        foreach ($tokensA as $i => $tokenA) {
            foreach ($tokensB as $j => $tokenB) {
                if ($tokenA === $tokenB) {
                    if (0 === $i || 0 === $j) {
                        $matrix[$i][$j] = 1;
                    } else {
                        $matrix[$i][$j] = $matrix[$i - 1][$j - 1] + 1;
                    }

                    $newIndex = $this->newIndex($matrix, $i, $j);
                    if ($matrix[$i][$j] > $longestLength) {
                        $longestLength = $matrix[$i][$j];
                        $longestIndexes = [$newIndex];
                    } elseif ($matrix[$i][$j] === $longestLength) {
                        $longestIndexes[] = $newIndex;
                    }
                } else {
                    $matrix[$i][$j] = 0;
                }
            }
        }

        return $this->result($longestIndexes, $longestLength, $tokensA, $matrix, $rstring);
    }
}
