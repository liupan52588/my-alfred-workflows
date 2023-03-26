<?php

class Tools_Hash extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];

        $passwordAlgo = [PASSWORD_DEFAULT, PASSWORD_BCRYPT]; // ** Users can add more algos here
        $algoListUse = $algoList = array_merge(hash_algos(), $passwordAlgo);

        if (strpos($query, '::') !== false) {
            $parts = explode('::', $query);
            $algo_q = array_shift($parts);
            $query = implode('::', $parts);

            $algoListUse = [];
            foreach ($algoList as $algo) {
                $pos = strpos($algo, $algo_q);
                if ($pos !== false && $pos == 0) {
                    $algoListUse[] = $algo;
                }
            }

        }
        foreach ($algoListUse as $algo) {
            if (in_array($algo, $passwordAlgo)) {
                $hash = password_hash($query, $algo);
            } else {
                $hash = hash($algo, $query);
            }
            $workFlowsRes[] = [
                'uid' => "hash-{$algo}",
                'title' => $hash,
                'subTitle' => $algo,
                'arg' => $hash,
                'valid' => true,
            ];
        }

        return $workFlowsRes;
    }
}
