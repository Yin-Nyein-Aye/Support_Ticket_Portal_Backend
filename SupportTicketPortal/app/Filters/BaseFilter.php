<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter
{
    protected Builder $query;
    protected array $filters;

    public function __construct(Builder $query, array $filters)
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    public function apply()
    {
        foreach ($this->filters as $key => $value) {

            if (method_exists($this, $key) && $value !== null) {
                $this->$key($value);
            }
        }
        return $this->query;
    }
}
