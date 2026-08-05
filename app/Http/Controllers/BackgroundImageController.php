<?php

namespace App\Http\Controllers;

use App\Models\BackgroundImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;

class BackgroundImageController extends Controller
{
    public function index()
    {
        return BackgroundImage::orderBy('id', 'DESC')->get();
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $this->validate($request, [
            'image' => 'required|image|max:8192',
            'is_light' => 'required|in:0,1',
        ], [
            'image.required' => 'Debe seleccionar una imagen',
            'image.image' => 'El archivo debe ser una imagen',
            'image.max' => 'La imagen no debe superar los 8MB',
        ]);

        $manager = new ImageManager(array('local' => 'imagick'));
        $file = $request->file('image');

        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = public_path('storage/images/backgrounds/' . $filename);

        $img = $manager->make($file->getRealPath());
        $img->resize(1920, 1200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($path);

        return BackgroundImage::create([
            'path' => 'storage/images/backgrounds/' . $filename,
            'is_light' => $request->input('is_light'),
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        $backgroundImage = BackgroundImage::findOrFail($id);

        $absolutePath = public_path(ltrim($backgroundImage->path, '/'));
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        $backgroundImage->delete();

        return response()->json(['message' => 'Imagen eliminada correctamente']);
    }

    private function authorizeAdmin()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            abort(403);
        }
    }
}
