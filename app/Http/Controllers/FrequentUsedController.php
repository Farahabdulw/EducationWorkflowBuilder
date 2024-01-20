<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FrequentUsed;
use DB;
class FrequentUsedController extends Controller
{
    public function index()
    {
        // $texts = FrequentUsed::get();
        return view('content.pages.frequent-used.index');
    }
    public function getTexts(){
        $texts = FrequentUsed::whereIn('id', function($query) {
            $query->select(DB::raw('MIN(id)'))
                ->from('frequent_useds')
                ->groupBy('text');
        })->get(['id', 'text']);        return response()->json([
            'success' => true,
            'texts' => $texts,
        ], 200);
    }
    public function delete(Request $request)
    {
        $frequentText = FrequentUsed::find($request->id);

        if (!$frequentText) {
            return response()->json(['error' => "Frequent text not found $request->id "], 404);
        }
        $frequentText->delete();

        return response()->json(['success' => true, 'message' => 'Frequent text soft deleted successfully'], 200);
    }
}
