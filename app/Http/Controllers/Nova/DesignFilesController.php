<?
namespace App\Http\Controllers\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignFilesController extends Controller
{
    public function listFiles($designId)
    {
        $files = Storage::disk('public')->files("files/{$designId}");

        // Transform files to a more readable format if needed
        return response()->json($files);
    }

    // Methods for download and delete...
}
