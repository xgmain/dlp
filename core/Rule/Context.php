<?php

namespace Core\Rule;

class Context{

    private $processor;

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function test()
    {
        if ($this->processor->test()) {
            return response([
                'message' => 'rule is validated',
                'data' => true,
            ], 200);
        }

        return response([
            'message' => 'rule validation fails',
            'data' => false,
        ], 200);
    }

    public function passed()
    {
        return $this->processor->check();
    }
}
