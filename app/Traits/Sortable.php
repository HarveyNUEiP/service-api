<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sortable
{
    /**
     * Sort query by column
     *
     * @param string $sort
     * @return $this
     */
    public function scopeSort($query, $sort)
    {
        $sort = Str::replace([',', '|'], ['&', '='], $sort);
        parse_str($sort, $sort);

        if (is_array($sort)) {
            foreach ($sort as $column => $direction) {
                if (in_array($column, $this->sortable)
                    && in_array(strtolower($direction), ['asc', 'desc'])) {
                    $query->orderBy($column, $direction);
                }
            }
        }

        return $query;
    }
}
