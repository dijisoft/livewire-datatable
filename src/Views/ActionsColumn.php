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
                        $html .= '<a href="'.(($action['route']??false)? route($action['route'], $row->id) : ($action['url']?? '#')) .'"  data-id="'.$row->id.'"
                            ' .($action['attr']??'').' class="btn btn-sm '.($action['class']??'btn-primary').' '.($i>0?'ml-1':'').'">'.($action['title']??'').'</a>';
                            break;
                    }
                    case 'btn-icon': {
                        $html .= '<a href="'. (($action['route']??false)? route($action['route'], $row->id) : ($action['url']?? '#')) .'" data-id="'.$row->id.'"
                        ' .($action['attr']??'').' class="btn btn-icon btn-round '.($action['class']??'btn-trigger').' '.($i>0?'ml-1':'').'"><em class="icon ni ni-'.($action['icon']??'').'"></em></a>';
                            break;
                    }
                    case 'wire-btn': {
                        $html .= '<button wire:click.prevent="'. Str::replace(['{id}'], "$row->id", $action['click']) .'"
                            '.(($action['confirm']??false)? 'onclick="confirm(\'??tes vous s??r(e) de vouloir '.$action['confirm'].'?\') || event.stopImmediatePropagation()"' : '').'
                            class="btn btn-sm '.($action['class']??'btn-primary').' '.($i>0?'ml-1':'').'">'.($action['title']??'').'</button>';
                            break;
                    }
                    case 'wire-btn-icon': {
                        $html .= '<button wire:click.prevent="'. Str::replace(['{id}'], "$row->id", $action['click']) .'"
                            '.(($action['confirm']??false)? 'onclick="confirm(\'??tes vous s??r(e) de vouloir '.$action['confirm'].'?\') || event.stopImmediatePropagation()"' : '').'
                            class="btn btn-icon btn-round '.($action['class']??'btn-trigger').' '.($i>0?'ml-1':'').'"><em class="icon ni ni-'.($action['icon']??'').'"></em></button>';
                            break;
                    }
                }
            }
            return $html;
        })->asHtml();
    }
}