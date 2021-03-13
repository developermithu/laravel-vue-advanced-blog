<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        return Tag::latest()->get();
    }

    public function addTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required | unique:tags'
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required | unique:tags',
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function destroy(Request $request)
    {
        return Tag::where('id', $request->id)->delete();
    }

    public function uploadImage(Request $request)
    {
        //  :on-error="handleError"  handleError(res, file) to show $request validation
        $this->validate($request, [
            'file' => 'required | image | mimes:jpg,jpeg,png | max:2048',
        ]);
        $imageName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('media'), $imageName);
        return $imageName;
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'Done';
    }

    public function deleteFileFromServer($fileName)
    {
        $filePath = public_path() . '/media/' . $fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }
}
