<?php

namespace Triun\LongestCommonSubstring;

/**
 * Interface SolverInterface
 *
 * @package Triun\LongestCommonSubstring
 */
interface SolverInterface
{
    /**
     * @param string|string[] $stringA
     * @param string|string[] $stringB
     * @param bool            $string
     *
     * @return string
     */
    public function solve($stringA, $stringB, $string = true);
}
