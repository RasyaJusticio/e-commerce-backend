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
}
