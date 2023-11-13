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
        $form->categories()->attach($categories);

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Form added successfully'], 200);
    }
    public function get_forms(Request $request)
    {
        
    }
}
