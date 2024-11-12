<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class CustomTab extends Tool
{
    /**
     *      * Get the displayable name of the tool.
     *           *
     *                * @return string
     *                     */
    public function name()
    {
        return 'Custom Tab';
    }

    /**
     *      * Perform any tasks that need to happen when the tool is booted.
     *           *
     *                * @return void
     *                     */
    public function boot()
    {
        // Code to execute on tool boot
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new Card())->html('<h1>Custom Tab Content</h1><p>This is some hardcoded HTML content.</p>'),
        ];
    }

    /**
     * Get the tool's navigation menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Nova\Menu|\Closure|null
     */
    public function menu(Request $request)
    {
        return null;
    }
}