<?php

namespace Dijisoft\LivewireDatatable\Traits;
use Validator;

/**
 * Trait WithFilters.
 */
trait WithLiveEdit
{
    /**
     */
    public $editableModelName = '';
    public $editableModel = null;

    public $editedIndex = null;
    public $editedField = null;
    public $editedValue = null;

    public function setEditableModel($modelName, $model)
    {
        $this->editableModelName = $modelName;
        $this->editableModel = $model;
    }

    public function editIndex($index, $field, $value='')
    {
        $this->editedIndex = $index;
        $this->editedField = $field;
        $this->editedValue = $value;
    }

    public function saveEditable()
    {
        if($this->editedValue) {
            $keyValuePair = [$this->editedField => $this->editedValue];
            if($this->rules[$this->editedField]??false) {
                Validator::make($keyValuePair, [
                    $this->editedField => $this->rules[$this->editedField]
                ])->validateWithBag($this->editedField);
            }
            if($row = $this->editableModel::find($this->editedIndex)) {
                $row->update($keyValuePair);
            }
        }

        $this->resetEditable();
    }

    public function resetEditable()
    {
        $this->reset('editedIndex', 'editedValue');
    }
}
