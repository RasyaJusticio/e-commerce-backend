<?php

namespace App\Services;

class QueryProcessor
{
    public function paginate($query, $page = 1, $per_page = 10)
    {
        if ($page < 1) {
            $page = 1;
        }

        if ($per_page < 1) {
            $per_page = 10;
        }

        return $query
            ->offset(($page - 1) * $per_page)
            ->limit($per_page);
    }

    public function sort($query, $sort_by = 'id', $sort_order = 'asc')
    {
        if (strtolower($sort_order) !== 'asc' && strtolower($sort_order) !== 'desc') {
            $sort_order = 'asc';
        }

        return $query->orderBy($sort_by, $sort_order);
    }
}
