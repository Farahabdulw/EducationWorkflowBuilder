<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forms;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PdfController extends Controller
{

    public function generate_document(Request $request)
    {
        $form = Forms::find($request->form);
        if (!$form)
            return response()->json(['success' => false], 404);
        $content = $request->content;
        Session::put('form', $form);
        Session::put('content', $content);

        // return response()->json(['route' => route('test')], 200);
        $pdf = PDF::loadView('layouts.formTemplate', compact('form', 'content'));

        $filePath = storage_path('app/private/forms/form-' . $form->id . '.pdf');
        $pdf->save($filePath);

        return response()->json(['success' => true], 200);

    }
    public function test()
    {
        $form = session('form');
        $content = session('content');

        return view('layouts.formTemplate', compact('form', 'content'));
    }
}
