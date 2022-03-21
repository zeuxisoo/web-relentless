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

        return match($className) {
            Program::class => $this->produceProgram($node),

            // Statements
            DateSingleStatement::class => $this->produceDateSingleStatement($node),

            // Expressions
            TimeExpression::class => $this->produceTimeExpression($node),
            TagsExpression::class => $this->produceTagsExpression($node),
            TagExpression::class => $this->produceTagExpression($node),
            FoodsExpression::class => $this->produceFoodsExpression($node),
            FoodExpression::class => $this->produceFoodExpression($node),
            RemarkExpression::class => $this->produceRemarkExpression($node),

            default => $this->stopGenerator($node),
        };
    }

    public function stopGenerator(Node $node): never {
        throw new GeneratorException($node);
    }

}
