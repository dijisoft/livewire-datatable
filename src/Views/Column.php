<?php

namespace Dijisoft\LivewireDatatable\Views;

use Illuminate\Support\Str;

/**
 * Class Column.
 */
class Column
{
    /**
     * @var string|null
     */
    public ?string $column = null;

    /**
     * @var string|null
     */
    public ?string $text = null;

    /**
     * @var bool
     */
    public bool $sortable = false;

    /**
     * @var
     */
    public $sortCallback;

    /**
     * @var bool
     */
    public bool $searchable = false;

    /**
     * @var callable
     */
    public $searchCallback;

    /**
     * @var string|null
     */
    public ?string $class = 'nk-tb-col';

    /**
     * @var bool
     */
    public bool $blank = false;

    /**
     * @var
     */
    public $formatCallbacks;

    /**
     * @var bool
     */
    public bool $asHtml = false;

    /**
     * @var bool
     */
    public bool $hidden = false;

    /**
     * @var string
     */
    public string $theme = 'default';

     /**
     * @var string
     */
    public string $editable = '';

    /**
     * Column constructor.
     *
     * @param string|null $column
     * @param string|null $text
     */
    public function __construct(string $text = null, string $column = null)
    {
        $this->text = $text;

        if (! $column && $text) {
            $this->column = Str::snake($text);
        } else {
            $this->column = $column;
        }

        if (! $this->column && ! $this->text) {
            $this->blank = true;
        } else {
            $this->text = $this->text;
        }

        $this->formatCallbacks = [];
    }

    /**
     * @param string|null $column
     * @param string|null $text
     *
     * @return Column
     */
    public static function make(string $text = null, string $column = null): Column
    {
        return new static($text, $column);
    }

    /**
     * @return Column
     */
    public static function blank(): Column
    {
        return new static(null, null);
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable === true;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable === true;
    }

    /**
     * @return bool
     */
    public function isBlank(): bool
    {
        return $this->blank === true;
    }

    /**
     * @return $this
     */
    public function sortable($callback = null): self
    {
        $this->sortable = true;

        $this->sortCallback = $callback;

        return $this;
    }

    /**
     * @param callable|null $callback
     * @return $this
     */
    public function searchable($params = null): self
    {
        $callback = $params;
        if(is_array($params)) {
            $callback = (function($query, $search) use ($params) {
                foreach ($params as $field) {
                    $parts = explode('.', $field);
                    if(count($parts) > 1) {
                        $_parts = [
                            implode('.', explode('.', $field, -1)),
                            last($parts)
                        ];
                        $query = $query->orWhereHas($_parts[0], fn($q) => $q->where($_parts[1], 'like', '%' . $search . '%'));
                    } else {
                        $query = $query->orWhere($field, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        $this->searchable = true;
        $this->searchCallback = $callback;

        return $this;
    }

    public function searchableIf($condition, $params = null): self
    {
        if($condition) {
            return $this->searchable($params);
        }

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function addClass(string $class): self
    {
        $this->class .= " $class";

        return $this;
    }

    /**
     * @return Column
     */
    public function asHtml(): Column
    {
        $this->asHtml = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function class(): ?string
    {
        return $this->class;
    }

    /**
     * @return string|null
     */
    public function column(): ?string
    {
        return $this->column;
    }

    /**
     * @return string|null
     */
    public function text(): ?string
    {
        return $this->text;
    }

    /**
     * @param  callable  $callable
     *
     * @return $this
     */
    public function format(callable $callable): Column
    {
        $this->formatCallbacks[] = $callable;

        return $this->asHtml();
    }

    /**
     * @param $row
     * @param  null  $column
     *
     * @return array|mixed|null
     */
    public function formatted($row, $column = null)
    {
        if ($column instanceof self) {
            $columnName = $column->column();
        } elseif (is_string($column)) {
            $columnName = $column;
        } else {
            $columnName = $this->column();
        }

        $value = data_get($row, $columnName);

        foreach ($this->formatCallbacks as $callable) {
            $value = app()->call($callable, ['value' => $value, 'column' => $column, 'row' => $row]);
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function hasSortCallback(): bool
    {
        return $this->sortCallback !== null;
    }

    /**
     * @return callable|null
     */
    public function getSortCallback(): ?callable
    {
        return $this->sortCallback;
    }

    /**
     * @return bool
     */
    public function hasSearchCallback(): bool
    {
        return $this->searchCallback !== null;
    }

    /**
     * @return callable|null
     */
    public function getSearchCallback(): ?callable
    {
        return $this->searchCallback;
    }

    /**
     * @param $condition
     *
     * @return $this
     */
    public function hideIf($condition): self
    {
        $this->hidden = $condition? true : false;

        return $this;
    }

     /**
     * @param $condition
     *
     * @return $this
     */
    public function showIf($condition): self
    {
        $this->hidden = $condition? false : true;

        return $this;
    }

    /**
     * @return bool
     */
    public function hideXs($condition = null): self
    {
        if(is_null($condition) || $condition) {
            return $this->addClass('hidden-xs');
        }
        
        return $this;
    }

    public function showXs($condition = null): self
    {
        if(is_null($condition) || $condition) {
            return $this->addClass('d-sm-none');
        }
        
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->hidden !== true;
    }

    /**
     * @return view
     */
    public function view($view, $class='')
    {
        $this->format(
            fn ($value, $column, $row) => view("datatables::elements.$view", ['class' => $class, 'value' => $value, 'column' => $column, 'row' => $row])
        );

        return $this->asHtml();
    }

     /**
     * @return Column
     */
    public function theme(string $theme)
    {
        $this->theme = $theme?: 'default';

        return $this;
    }

    /**
     * @return Column
     */
    public function editable(string $editable)
    {
        $this->editable = $editable;

        return $this;
    }
}
