<?php

namespace App\Filters;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class CourseFilters extends BaseFilter
{
    public function search(Builder $query): Builder
    {
        return $query->where('name', 'like', '%' . $this->request->input('search') . '%');
    }

    public function categoryId(Builder $query) : Builder {
        return $query->where('category_id',$this->request->input('categoryId'));
    }

}
