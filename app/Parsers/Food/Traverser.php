<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Ast\Program;
use App\Parsers\Food\Ast\Statements\DateGroupStatement;
use App\Parsers\Food\Ast\Statements\DateSingleStatement;

class Traverser {

    protected Program $program;

    public function __construct(
        public Parser $parser
    ) {
        $this->program = $parser->parse();
    }

    public function traverse(): Program {
        $statements = [];

        foreach($this->program->statements as $statement) {

            // Flatten DateGroupStatement to DateSingleStatement
            if ($statement instanceof DateGroupStatement) {
                foreach($statement->members->values as $member) {
                    $statements[] = new DateSingleStatement(
                        value : $statement->value,
                        time  : $member->time,
                        tags  : $member->tags,
                        foods : $member->foods,
                        remark: $member->remark,
                    );
                }

                continue;
            }

            // Other statements
            $statements[] = $statement;

        }

        return new Program($statements);
    }

}
