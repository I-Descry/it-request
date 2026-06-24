<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HierarchyController extends Controller
{
    private $filePath = 'hierarchy.json';

    public function index()
    {
        $hierarchy = $this->getHierarchy();
        return view('employees.hierarchy', compact('hierarchy'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hierarchy_json' => 'required|json',
        ]);

        $hierarchy = json_decode($validated['hierarchy_json'], true);

        // Optional: sanitize and trim everything
        $cleanHierarchy = [];
        foreach ($hierarchy as $dept => $positions) {
            $dept = trim($dept);
            if (!empty($dept)) {
                $cleanPositions = array_values(array_filter(array_map('trim', (array)$positions)));
                $cleanHierarchy[$dept] = $cleanPositions;
            }
        }

        Storage::disk('local')->put($this->filePath, json_encode($cleanHierarchy, JSON_PRETTY_PRINT));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Hierarchy updated successfully!']);
        }

        return redirect()->route('employees.index')->with('success', 'Hierarchy updated successfully!');
    }

    public static function getHierarchy()
    {
        $filePath = 'hierarchy.json';
        if (!Storage::disk('local')->exists($filePath)) {
            $default = [
                'IT' => ['IT Assistant', 'IT Specialist', 'Network Engineer', 'Systems Administrator'],
                'Finance' => ['Accountant', 'Finance Manager'],
                'HR' => ['HR Manager', 'Recruiter']
            ];
            Storage::disk('local')->put($filePath, json_encode($default, JSON_PRETTY_PRINT));
            return $default;
        }

        $data = json_decode(Storage::disk('local')->get($filePath), true);
        
        // Safety check: if old format (e.g., has "departments" array instead of department keys), reset to default
        if (isset($data['departments']) && isset($data['positions'])) {
            $default = [
                'IT' => ['IT Assistant', 'IT Specialist'],
                'Finance' => ['Accountant', 'Finance Manager'],
                'HR' => ['HR Manager', 'Recruiter']
            ];
            Storage::disk('local')->put($filePath, json_encode($default, JSON_PRETTY_PRINT));
            return $default;
        }

        return $data;
    }
}
