<?php

namespace Dijisoft\LivewireDatatable;

use Livewire\Component;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use Dijisoft\LivewireDatatable\Traits\WithBulkActions;
use Dijisoft\LivewireDatatable\Traits\WithCustomPagination;
use Dijisoft\LivewireDatatable\Traits\WithFilters;
use Dijisoft\LivewireDatatable\Traits\WithPerPagePagination;
use Dijisoft\LivewireDatatable\Traits\WithSearch;
use Dijisoft\LivewireDatatable\Traits\WithSorting;
use Dijisoft\LivewireDatatable\Utilities\ColumnSet;
use Dijisoft\LivewireDatatable\Exports\DatatableExport;

/**
 * Class TableComponent.
 *
 * @property LengthAwarePaginator|Collection|null $rows
 */
class LivewireDatatable extends Component
{
    use WithBulkActions;
    use WithCustomPagination;
    use WithFilters;
    use WithPerPagePagination;
    use WithSearch;
    use WithSorting;

    public $theme = 'default';

    /**
     * Whether or not to refresh the table at a certain interval
     * false is off
     * If it's an integer it will be treated as milliseconds (2000 = refresh every 2 seconds)
     * If it's a string it will call that function every 5 seconds unless it is 'keep-alive' or 'visible'.
     *
     * @var bool|string
     */
    public $refresh = false;

    /**
     * Whether or not to display an offline message when there is no connection.
     *
     * @var bool
     */
    public bool $offlineIndicator = true;

    /**
     * The message to show when there are no results from a search or query
     *
     * @var string
     */
    public string $emptyMessage = 'No results';

    /**
     * Name of the page parameter for pagination
     * Good to change the default if you have more than one datatable on a page.
     *
     * @var string
     */
    protected string $pageName = 'page';

    /**
     * @var \null[][]
     */
    protected $queryString = [
        'filters' => ['except' => null],
        'sorts' => ['except' => null],
    ];

    /**
     * @var string[]
     */
    protected $listeners = [
        'refreshDatatable' => '$refresh',
        'setDatatableVariable' => 'setDatatableVariable'
    ];

    /** 
    * Check table is ready to load
    *
    */
    public $loader;
    public $readyToLoad;
    public $deferLoad;

    /** 
    *
    */
    public $layout;
    public $title;
    public $metaTitle;
    public $model;
    public $include;
    public $exclude;
    public $searchable;
    public $sortable;
    public $hidden;
    public $actions;
    public $styles;
    public $create;
    public $createPermissions;
    public $restrict;
    public $defaultSorting;
    public $limit;
    public $components = [];
    public $customFiltersView;
    public $importActions = [];
    public $hide = [];

    public function mount(
        $layout = 'default',
        $title = null,
        $model = null,
        $include = null,
        $exclude = null,
        $searchable = null,
        $sortable = null,
        $hidden = null,
        $actions = null,
        $create = null,
        $restrict = null,
        $defaultSorting = null,
        $limit = null,
        $components = [],
        $customFiltersView = null,
        $loader = null,
        $deferLoad = false,
        $styles = [],
        $hide = [],
    ) {
        
        $this->filters = array_merge($this->filters, $this->baseFilters);
        $this->readyToLoad = false;
        
        foreach (['layout', 'title', 'model', 'include', 'searchable', 'sortable', 'hidden', 'actions', 
            'create', 'restrict', 'defaultSorting', 'limit', 'components', 'customFiltersView', 'loader', 'deferLoad', 
            'styles', 'hide'] as $property) {
            $this->$property = $this->$property ?? $$property;
        }

        if($this->limit) {
            $this->paginationEnabled = false;
            $this->showPagination = false;
        }
        
        $this->resetSorts();
        $this->onMount();
    }

    public function onMount() {
        return true;
    }

    public function makeColumns() {
        return $this->model_instance;
    }

    /**
     * The array defining the columns of the table.
     *
     * @return array
     */
    public function columns(): array {
        // if(empty($this->model)) {
        //     return [];
        // }
        
        return ColumnSet::build($this->makeColumns())
                ->include($this->include)
                ->exclude($this->exclude)
                ->hidden($this->hidden)
                ->searchable($this->searchable)
                ->sortable($this->sortable)
                ->theme($this->theme)
                ->actions($this->actions)
                ->columnsArray();
    }

    /**
     * The base query with search and filters for the table.
     *
     * @return Builder|Relation
     */
    public function query() : Builder  { 
        return $this->model? $this->model::query() : null;
    }

    /**
     * Get the rows query builder with sorting applied.
     *
     * @return Builder|Relation
     */
    public function rowsQuery()
    {
        // $this->cleanFilters();

        $query = $this->query()->when($this->limit, fn($q) => $q->limit($this->limit));

        if(is_array($this->restrict)) {
            foreach($this->restrict as $column => $value) {
                $query = $query->where($column, $value);
            }
        }

        if(is_string($this->defaultSorting) && empty($this->sorts??null)) {
            $parts = explode('|', $this->defaultSorting);
            $query = $query->orderBy($parts[0], $parts[1]??'asc');
        }

        if (method_exists($this, 'applySorting')) {
            $query = $this->applySorting($query);
        }

        if (method_exists($this, 'applyFilters')) {
            $query = $this->applyFilters($query);
        }

        if (method_exists($this, 'applyCustomFilters')) {
            $query = $this->applyCustomFilters($query);
        }

        return $query;
    }

    /**
     * Get the rows paginated collection that will be returned to the view.
     *
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function getRowsProperty()
    {
        if(! $this->can_load) {
            return collect();
        }

        if ($this->paginationEnabled) {
            return $this->applyPagination($this->rowsQuery());
        }

        $rows = $this->rowsQuery()->get();

        if (method_exists($this, 'applyCollectionFilters')) {
            $rows = $this->applyCollectionFilters($rows);
        }

        return $rows;
    }

    public function getTableNameProperty() {
        $path = request()->getContent()? json_decode(request()->getContent())->fingerprint->path??'' : request()->path();
        return $path . last(explode('\\', get_class($this)));
    }

    public function getModelInstanceProperty() {
        return $this->model? new $this->model() : null;
    }

    public function setDatatableVariable($variable, $value) {
        $this->{$variable} = $value;
    }

    public function resetProperty($property): void
    {
        $this->reset($property);
    }

    /**
     * Reset all the criteria
     */
    public function resetAll(): void
    {
        $this->resetFilters();
        $this->resetSearch();
        $this->resetSorts();
        $this->resetBulk();
        $this->resetPage();
    }

    /**
     * The view to render each row of the table.
     *
     * @return string
     */
    public function rowView(): string
    {
        return 'datatables::'.$this->theme.'.components.table.row-columns';
    }

    /**
     * @return mixed
     */
    public function render()
    {
        return view('datatables::'.$this->theme.'.layout.'.$this->layout)
            ->with([
                'columns' => $this->columns(),
                'rowView' => $this->rowView(),
                'filtersView' => $this->filtersView(),
                'filtersList' => $this->getFiltersWithoutBtn(),
                'btnFilters' => $this->getFiltersBtn(),
                'rows' => $this->rows,
            ])
            ->layoutData(['metaTitle' => $this->metaTitle?? $this->title]);
    }

    /**
     * Get a column object by its field
     *
     * @param  string  $column
     *
     * @return mixed
     */
    protected function getColumn(string $column)
    {
        return collect($this->columns())
            ->where('column', $column)
            ->first();
    }

    public function export()
    {
        return (new DatatableExport($this->rowsQuery()->get(), $this->columns()))->download(__('TableExport').'.csv', 'Csv');
    }

    public function loadTable()
    {
        $this->readyToLoad = true;
    }

    public function getCanLoadProperty() {
        return (!$this->loader && !$this->deferLoad) || ($this->loader && $this->readyToLoad);
    }

    public function paginationView()
    {
        return 'vendor.livewire.default-pagination';
    }
}
