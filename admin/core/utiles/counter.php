<?php

class Counter {

    static $i = 0;
    static $creator;

    public static function counter() {
        self::$i ++;
        return self::$i;
    }

    public static function create() {

        return self::$creator = new Counter();

    }

    public function local_counter($start = 0, $end = 10000, $step = 1) {

        if ($start <= $end) {
            if ($step <= 0) {
                throw new LogicException('Step must be positive');
            }

            for ($i = $start; $i <= $end; $i += $step) {
                yield $i;
            }
        } else {
            if ($step >= 0) {
                throw new LogicException('Step must be negative');
            }

            for ($i = $start; $i >= $end; $i += $step) {
                yield $i;
            }
        }

    }

}