<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Program;

trait ProgramProducer {

    protected function produceProgram(Program $program): array {
        $codes = [];

        foreach($program->statements as $statement) {
            $codes[] = $this->produce($statement);
        }

        return $codes;
    }

}
