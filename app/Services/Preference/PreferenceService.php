<?php

namespace App\Services\Preference;

use App\Models\Preference\Preference;

class PreferenceService
{
    public function createPreference($request)
    {
        $preference = new Preference;

        $preference->user_id = $request->user()->id;
        $preference->category_id = $request->input('category_id');
        
        $preference->save();

        return response()->json(['message' => 'Preference saved successfully!'], 200);
    }

    public function listPreference($user_id)
    {
        $preference = Preference::where('user_id', $user_id)->get();
    
        return response()->json($preference, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function updatePreference($request, $preference)
    {
        $preference->category_id = $request->input('category_id');
        
        $preference->save();

        return response()->json(['message' => 'Preference updated successfully!'], 200);
    }

    public function deletePreference($preference)
    {
        $preference->delete();
        
        return response()->json(['message' => 'Preference successfully deleted!'], 200);
    }
}