<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forms;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use TCPDF;

class PdfController extends Controller
{

    public function generate_document(Request $request)
    {
        $form = $request->input('form');

        $form = Forms::find($form);

        if (!$form) {
            return response()->json(['success' => false], 404);
        }

        try {
            $request->file('pdf')->storeAs('private/forms', 'form-' . $form->id . '.pdf', 'local');

            return response()->json(['message' => 'PDF saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save PDF'], 500);
        }

        return response()->json(['success' => true], 200);

    }
    public function test()
    {
        $form = session('form');
        $content = session('content');

        return view('layouts.formTemplate', compact('form', 'content'));
    }
}
