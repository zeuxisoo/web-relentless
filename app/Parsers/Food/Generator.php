<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Ast\Contracts\Node;
use App\Parsers\Food\Ast\Program;
use App\Parsers\Food\Ast\Statements\DateSingleStatement;
use App\Parsers\Food\Exceptions\GeneratorException;
use App\Parsers\Food\Traits\Producers\DateProducer;
use App\Parsers\Food\Traits\Producers\ProgramProducer;

class Generator {

    use ProgramProducer, DateProducer;

    protected Program $program;

    public function __construct(
        public Traverser $traverser,
    ) {
        $this->program = $this->traverser->traverse();
    }

    public function generate(): array {
        return $this->produce($this->program);
    }

    protected function produce(Node $node): array {
        $className = get_class($node);

        switch($className) {
            case Program::class:
                return $this->produceProgram($node);
                break;
            // Statements
            case DateSingleStatement::class:
                return $this->produceDateSingleStatement($node);
                break;
            default:
                $this->stopGenerator($node);
                break;
        }
    }

    public function stopGenerator(Node $node): never {
        throw new GeneratorException($node);
    }

}
