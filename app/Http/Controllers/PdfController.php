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
        $form = Forms::find($request->form);

        if (!$form) {
            return response()->json(['success' => false], 404);
        }

        $content = $request->content;

        // Create a new TCPDF instance
        $pdf = new TCPDF();

        $pdf->SetTitle('Form');
        $pdf->AddPage();

        // Set font
        // $pdf->SetFont('arial', '', 12);

        // Add content to the PDF
        $pdf->writeHTML(view('layouts.formTemplate', compact('form', 'content'))->render());

        // Save the PDF to a file
        $filePath = storage_path('app/private/forms/form-' . $form->id . '.pdf');
        $pdf->Output($filePath, 'F');

        return response()->json(['success' => true], 200);



        // // return response()->json(['route' => route('test')], 200);
        // $pdf = PDF::loadView('layouts.formTemplate', compact('form', 'content'));

        // $filePath = storage_path('app/private/forms/form-' . $form->id . '.pdf');
        // $pdf->save($filePath);

        // return response()->json(['success' => true], 200);

    }
    public function test()
    {
        $form = session('form');
        $content = session('content');

        return view('layouts.formTemplate', compact('form', 'content'));
    }
}
