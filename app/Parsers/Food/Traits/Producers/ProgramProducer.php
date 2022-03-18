<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Program;

trait ProgramProducer {

    protected function produceProgram(Program $program): array {
        $statements = [];

        foreach($program->statements as $statement) {
            // TODO: produce each statement
        }

        return $statements;
    }

}
