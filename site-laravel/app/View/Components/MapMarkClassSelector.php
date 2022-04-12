<?php

namespace App\View\Components;

use Illuminate\View\Component;

define("MARK_TYPES", [
    ["label" => "Мусор", "type" => "TrashMark", "category" => ""],
    ["label" => "Экологические Мероприятия", "type" => "EventMark", "category" => ""],
    ["label" => "Мусорки", "type" => "DumpsterMark", "category" => ""],
    ["label" => "Прием одежды", "type" => "RecyclableMark", "category" => "Cloth"],
    ["label" => "Прием стеклотары и алюминия", "type" => "RecyclableMark", "category" => "Glass"],
    ["label" => "Прием пластика", "type" => "RecyclableMark", "category" => "Plastic"],
    ["label" => "Прием макулатуры", "type" => "RecyclableMark", "category" => "Paper"],
    ["label" => "Прием металлолома", "type" => "RecyclableMark", "category" => "Scrap"],
    ["label" => "Прием бытовой техники", "type" => "RecyclableMark", "category" => "Tech"],
    ["label" => "Прием аккумуляторов", "type" => "RecyclableMark", "category" => "Batteries"],
    //["label" => "Прием ртутных ламп", "type" => "RecyclableMark", "category" => "Bulbs"],
]);

class MapMarkClassSelector extends Component
{
    public $markTypes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->markTypes = MARK_TYPES;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.map-mark-class-selector');
    }
}
