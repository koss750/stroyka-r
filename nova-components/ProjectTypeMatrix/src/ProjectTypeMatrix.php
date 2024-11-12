<?php

namespace BorodinServices\ProjectTypeMatrix;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class ProjectTypeMatrix extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('project-type-matrix', __DIR__ . '/../../../public/vendor/project-type-matrix/js/tool.js');
        Nova::style('project-type-matrix', __DIR__ . '/../../../public/vendor/project-type-matrix/css/tool.css');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Project Type Matrix')
            ->path('/project-type-matrix')
            ->icon('server');
    }
}
