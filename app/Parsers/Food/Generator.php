<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Ast\Contracts\Node;
use App\Parsers\Food\Ast\Expressions\{
    FoodExpression,
    FoodsExpression,
    RemarkExpression,
    TagExpression,
    TagsExpression,
    TimeExpression,
};
use App\Parsers\Food\Ast\Program;
use App\Parsers\Food\Ast\Statements\DateSingleStatement;
use App\Parsers\Food\Exceptions\GeneratorException;
use App\Parsers\Food\Traits\Producers\{
    DateProducer,
    ProgramProducer,
    TagProducer,
    TagsProducer,
    TimeProducer,
    FoodsProducer,
    FoodProducer,
    RemarkProducer,
};

class Generator {

    use ProgramProducer;
    use DateProducer, TimeProducer;
    use TagsProducer, TagProducer;
    use FoodsProducer, FoodProducer;
    use RemarkProducer;

    protected Program $program;

    public function __construct(
        public Traverser $traverser,
    ) {
        $this->program = $this->traverser->traverse();
    }

    public function generate(): array {
        return $this->produce($this->program);
    }

    protected function produce(Node $node): array|string {
        $className = get_class($node);

        switch($className) {
            case Program::class:
                return $this->produceProgram($node);
                break;
            // Statements
            case DateSingleStatement::class:
                return $this->produceDateSingleStatement($node);
                break;
            // Expressions
            case TimeExpression::class:
                return $this->produceTimeExpression($node);
                break;
            case TagsExpression::class:
                return $this->produceTagsExpression($node);
                break;
            case TagExpression::class:
                return $this->produceTagExpression($node);
                break;
            case FoodsExpression::class:
                return $this->produceFoodsExpression($node);
                break;
            case FoodExpression::class:
                return $this->produceFoodExpression($node);
                break;
            case RemarkExpression::class:
                return $this->produceRemarkExpression($node);
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