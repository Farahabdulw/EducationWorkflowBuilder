<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Models\Forms;

class FormsController extends Controller
{
    public function index()
    {
        return view('content.pages.forms.index');
    }
    public function create()
    {
        return view('content.pages.forms.create');
    }
    public function edit()
    {
        return view('content.pages.forms.create');
    }
    public function create_category()
    {
        return view('content.pages.forms.category');
    }
    public function get_category()
    {
        $categories = Category::get();
        return response()->json($categories, 200);
    }
    public function add_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);


        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create a new category record in the database
        $category = Category::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        // Return a success response
        if ($category)
            return response()->json(['success' => true, 'message' => 'Category added successfully', 'category' => $category], 200);
        else
            return response()->json([
                'success' => false,
                'error' => $category,
            ], 422);
    }
    public function add(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'categories' => 'required|array',
            'formData' => 'required|json',
        ]);

        // Insert form data into the forms table
        $form = Forms::create([
            'name' => $request->get('title'),
            'content' => $request->get('formData'),
        ]);

        $categories = $request->get('categories');
        $form->categories()->sync($categories);

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Form added successfully'], 200);
    }
    public function get_forms(Request $request)
    {
        $forms = Forms::with('categories')->get();
        return response()->json($forms, 200);
    }
    public function get_form(Request $request, $id)
    {
        $form = Forms::with('categories')->find($id);
        if ($form)
            return response()->json($form, 200);
        else
            return response()->json(['error' => 'form not found'], 404);
    }
    public function get_content($id)
    {
        $formContent = Forms::select('content')->find($id);
        return response()->json($formContent, 200);
    }
    public function update(Request $request)
    {
        $form = Forms::find($request->id);
        if ($form) {
            $request->validate([
                'title' => 'required|string',
                'categories' => 'required|array',
                'formData' => 'required|json',
            ]);
            // Insert form data into the forms table
            $form->name = $request->get('title');
            $form->content = $request->get('formData');
            $form->save();
            $categories = $request->get('categories');
            $form->categories()->sync($categories);

            // Return a success response
            return response()->json(['success' => true, 'message' => 'Form updated successfully'], 200);
        } else
            return response()->json(['success' => FALSE, 'message' => 'Form not found'], 404);

    }
}
