<?php

namespace Dijisoft\LivewireDatatable\Views;
use Str;

class ActionsColumn extends Column
{
    public array $actions;

    public static function make(string $text = null, string $column = null, string $textHtml = null): ActionsColumn
    {
        return new static($text?? 'Actions', $column, $textHtml);
    }

    public function actions(array $actions) {
        $this->actions = $actions;
        
        return $this->format(function($value, $row) use($actions) {
            $html = '';
            foreach($actions as $i => $action) {

                if(($action['showIf']??false) && !$action['showIf']($row)) {
                    continue;
                }
                switch($action['type']??null) {
                    case 'callable': {
                        if(is_callable($action['callback']??null)) {
                            $html .= $action['callback']($row);
                        }
                        break;
                    }
                    case 'btn': {
                        $html .= '<a href="'. $this->getRouteAction($action, $row) .'"  data-id="'.$row->id.'" '
                            .$this->getActionTitle($action, $row)
                            .data_get($action, 'attr', '')
                            .' class="btn btn-sm '.($action['class']??'btn-primary').' '.($i>0?'ml-1':'').'">'
                            .data_get($action, 'title', '').'</a>';
                            break;
                    }
                    case 'btn-icon': {
                        $html .= '<a href="'. $this->getRouteAction($action, $row) .'" data-id="'.$row->id.'" '
                            .$this->getActionTitle($action, $row)
                            .data_get($action, 'attr', '')
                            .' class="btn btn-icon btn-round '.data_get($action, 'class', 'btn-trigger').' '.($i>0?'ml-1':'').'"><em class="icon ni ni-'
                            .data_get($action, 'icon', '').'"></em></a>';
                            break;
                    }
                    case 'wire-btn': {
                        $html .= '<button wire:click.prevent="'. $this->getClickAction($action, $row) .'" '
                            .$this->getActionTitle($action, $row)
                            .$this->getActionConfirm($action, $row)
                            .'class="btn btn-sm '.($action['class']??'btn-primary').' '.($i>0?'ml-1':'').'">'
                            .data_get($action, 'title'. '').'</button>';
                            break;
                    }
                    case 'wire-btn-icon': {
                        $html .= '<button wire:click.prevent="'. $this->getClickAction($action, $row) .'" '
                            .$this->getActionTitle($action, $row)
                            .$this->getActionConfirm($action, $row)
                            .'class="btn btn-icon btn-round '.data_get($action, 'class', 'btn-trigger').' '.($i>0?'ml-1':'').'"><em class="icon ni ni-'
                            .data_get($action, 'icon', '').'"></em></button>';
                            break;
                    }
                }
            }
            return $html;
        })->asHtml();
    }

    private function getActionTitle($action, $row) {
        if(is_callable(data_get($action, 'title'))) {
            return $action['title']($row);
        }

        return data_get($action, 'title') ? 'x-data="tooltip" title="'.$action['title'].'"' : '';
    }

    private function getActionConfirm($action, $row) {
        if(is_callable(data_get($action, 'confirm'))) {
            return $action['confirm']($row);
        }

        return data_get($action, 'confirm') ? 'onclick="confirm(\''.$action['confirm'].'\') || event.stopImmediatePropagation()"' : '';
    }

    private function getRouteAction($action, $row) {
        if(is_callable(data_get($action, 'route'))) {
            return $action['route']($row);
        }

        return data_get($action, 'route') ? route($action['route'], $row) : data_get($action, 'url', '#');
    }

    private function getClickAction($action, $row) {
        if(is_callable(data_get($action, 'click'))) {
            return $action['click']($row);
        }

        return Str::replace(['{id}'], "$row->id", data_get($action, 'click'));
    }
}