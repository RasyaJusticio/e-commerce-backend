<?php

namespace App\Services;

class QueryProcessor
{
    public function filter($query, $filter = [], $case_sensitive = false)
    {
        if (count($filter) > 0) {
            $query->where(function ($query) use ($filter, $case_sensitive) {
                foreach ($filter as $key => $value) {
                    if ($case_sensitive) {
                        $query->orWhere($key, 'LIKE', '%' . $value . '%');
                    } else {
                        $query->orWhere($key, 'ILIKE', '%' . $value . '%');
                    }
                }
            });
        }

        return $query;
    }

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
