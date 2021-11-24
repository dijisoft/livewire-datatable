<?php

namespace Dijisoft\LivewireDatatable\Traits;

/**
 * Trait WithHighlight.
 */
trait WithHighlight
{
    /*  This can be called to apply highlting of the search term to some string.
     */
    public function highlightStringWithCurrentSearchTerm(string $originalString)
    {
        return $this->show_search? 
            $this->highlightString($originalString, $this->filters['search']??'') : $originalString; 
    }

    /* Utility function for applying highlighting to given string */
    public function highlightString(string $originalString, string $searchingForThisSubstring)
    {
        $searchStringNicelyHighlightedWithHtml = view(
            'datatables::elements.highlight',
            ['slot' => $searchingForThisSubstring]
        )->render();
        $stringWithHighlightedSubstring = str_ireplace(
            $searchingForThisSubstring,
            $searchStringNicelyHighlightedWithHtml,
            $originalString
        );

        return $stringWithHighlightedSubstring;
    }

    public function applyHighlight($value, $string)
    {
        $output = substr($value, stripos($value, $string), strlen($string));

        if ($value instanceof View) {
            return $value
                ->with(['value' => str_ireplace($string, (string) view('datatables::elements.highlight', ['slot' => $output]), $value->gatherData()['value'] ?? $value->gatherData()['slot'])]);
        }

        return str_ireplace($string, (string) view('datatables::elements.highlight', ['slot' => $output]), $value);
    }
}
