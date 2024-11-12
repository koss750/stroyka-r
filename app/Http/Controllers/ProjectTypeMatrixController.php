<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;
use App\Http\Controllers\RuTranslationController as Translator;

class ProjectTypeMatrixController extends Controller
{
    public function index()
    {
        $categories = ProjectType::distinct('category')->pluck('category');
        $sizes = ProjectType::distinct('size')->orderBy('size')->pluck('size');
        $projectTypes = ProjectType::all()->groupBy('category');

        $matrix = [];
        foreach ($categories as $category) {
            $matrix[$category] = [];
            foreach ($sizes as $size) {
                $projectType = $projectTypes[$category]->firstWhere('size', $size);
                $matrix[$category][$size] = $projectType ? $projectType->price : null;
            }
        }

        return view('nova.project-type-matrix', [
            'matrix' => $matrix,
            'categories' => $categories,
            'sizes' => $sizes,
            'translations' => [
                'category' => Translator::translate('category'),
                'size' => Translator::translate('size'),
                'price' => Translator::translate('price'),
                'save' => Translator::translate('save'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'matrix' => 'required|array',
        ]);

        foreach ($data['matrix'] as $category => $sizes) {
            foreach ($sizes as $size => $price) {
                ProjectType::updateOrCreate(
                    ['category' => $category, 'size' => $size],
                    ['price' => $price]
                );
            }
        }

        return response()->json(['message' => 'Prices updated successfully']);
    }
}