<?php

namespace App\Support\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;

class AggregateInclude implements IncludeInterface
{
    public function __construct(
        protected string $column,
        protected string $function
    )
    {
        $this->column = $column;
        $this->function = $function;
    }

    public function __invoke(Builder $query, string $relations)
    {
        $query->withAggregate($relations, $this->column, $this->function);
    }
}