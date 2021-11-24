<?php

namespace Dijisoft\LivewireDatatable\Utilities;

use Dijisoft\LivewireDatatable\Views\ActionsColumn;
use Dijisoft\LivewireDatatable\Views\Column;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ColumnSet
{
    public Collection $columns;

    public function __construct(Collection $columns)
    {
        $this->columns = $columns;
    }

    public static function build($input)
    {
        return is_array($input)
            ? self::fromArray($input)
            : self::fromModelInstance($input);
    }

    public static function fromModelInstance($model)
    {
        return new static(
            collect($model->getAttributes())->keys()->reject(function ($name) use ($model) {
                return in_array($name, $model->getHidden());
            })->map(function ($attribute) {
                return Column::name($attribute);
            })
        );
    }

    public static function fromArray($columns)
    {
        return new static(collect($columns??[]));
    }

    public function include($include)
    {
        if (! (is_array($include) || is_string($include))) {
            return $this;
        }

        $include = collect(is_array($include) ? $include : array_map('trim', explode(',', $include)));
        $this->columns = $include->map(function ($column) {
            return Str::contains($column, '|')
                ? Column::make(Str::after($column, '|'), Str::before($column, '|'))
                : Column::make($column);
        });

        return $this;
    }

    public function exclude($exclude)
    {
        if (! (is_array($exclude) || is_string($exclude))) {
            return $this;
        }

        $exclude = is_array($exclude) ? $exclude : array_map('trim', explode(',', $exclude));
        $this->columns = $this->columns->reject(function ($column) use ($exclude) {
            return in_array(Str::after($column->column, '.'), $exclude) || in_array($column->text, $exclude);
        });

        return $this;
    }

    public function hidden($hidden)
    {
        if (! (is_array($hidden) || is_string($hidden))) {
            return $this;
        }
        $hidden = is_array($hidden) ? $hidden : array_map('trim', explode(',', $hidden));
        $this->columns->each(function ($column) use ($hidden) {
            $column->hidden = in_array(Str::after($column->column, '.'), $hidden);
        });

        return $this;
    }

    public function searchable($searchable)
    {
        if (! (is_array($searchable) || is_string($searchable))) {
            return $this;
        }

        $searchable = is_array($searchable) ? $searchable : array_map('trim', explode(',', $searchable));
        $this->columns->each(function ($column) use ($searchable) {
            $column->searchable = in_array($column->column, $searchable);
        });

        return $this;
    }

    public function sortable($sortable)
    {
        if (! (is_array($sortable) || is_string($sortable))) {
            return $this;
        }

        $sortable = is_array($sortable) ? $sortable : array_map('trim', explode(',', $sortable));
        $this->columns->each(function ($column) use ($sortable) {
            $column->sortable = in_array($column->column, $sortable);
        });

        return $this;
    }

    public function sort($sort)
    {
        if ($sort && $column = $this->columns->first(function ($column) use ($sort) {
            return Str::after($column->column, '.') === Str::before($sort, '|');
        })) {
            $column->defaultSort(Str::of($sort)->contains('|') ? Str::after($sort, '|') : null);
        }

        return $this;
    }

    public function theme($theme)
    {
        if (! $theme) {
            return $this;
        }

        $this->columns->each(function ($column) use ($theme) {
            $column->theme = $theme;
        });

        return $this;
    }

    public function actions($actions)
    {
        if (! $actions) {
            return $this;
        }

        $this->columns->push(ActionsColumn::make()->actions($actions));

        return $this;
    }

    public function columns()
    {
        return collect($this->columns);
    }

    public function columnsArray()
    {
        return $this->columns()->toArray();
    }
}
