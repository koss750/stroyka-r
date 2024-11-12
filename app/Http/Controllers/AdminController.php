<?php

namespace App\Http\Controllers;

use App\Services\CaptionService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $captionService;

    public function __construct(CaptionService $captionService)
    {
        $this->captionService = $captionService;
        $this->middleware('can:access-admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function editCaptions()
    {
        $captions = $this->captionService->all();
        return view('admin.edit-captions', compact('captions'));
    }

    public function updateCaptions(Request $request)
    {
        $captions = $request->input('captions');
        foreach ($captions as $key => $value) {
            $this->captionService->update($key, $value);
        }
        return redirect()->back()->with('success', __('captions.captions_updated_successfully'));
    }
}