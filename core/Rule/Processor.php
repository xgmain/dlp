<?php

namespace Core\Rule;

interface Processor{
    public function check();
    public function test();
    public function filter(String $string);
    public function reference(String $string);
}