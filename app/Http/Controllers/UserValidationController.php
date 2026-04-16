<?php

namespace App\Http\Controllers;

use App\Models\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserValidationController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $existingValidation = $user->userValidation;
        
        if ($existingValidation) {
            return redirect()->route('profile.show', $user->id)
                ->with('info', 'You already have a validation request submitted.');
        }
        
        return view('validation.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has a validation
        if ($user->userValidation) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'You already have a validation request submitted.');
        }

        $validator = Validator::make($request->all(), [
            'id_type' => 'required|in:national_id,government_id,umid_id,passport,drivers_license,student_id,other',
            'id_number' => 'required|string|max:255',
            'id_front_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'id_back_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Store images with error handling
        try {
            $frontImagePath = $request->file('id_front_image')->store('validations', 'public');
            \Log::info('Front image stored successfully', [
                'user_id' => $user->id,
                'path' => $frontImagePath,
                'original_name' => $request->file('id_front_image')->getClientOriginalName(),
                'size' => $request->file('id_front_image')->getSize()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to store front image', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file_info' => $request->file('id_front_image') ? 'File exists' : 'No file'
            ]);
            return redirect()->back()
                ->with('error', 'Failed to upload front image: ' . $e->getMessage())
                ->withInput();
        }

        try {
            $backImagePath = $request->file('id_back_image') 
                ? $request->file('id_back_image')->store('validations', 'public') 
                : null;
                
            if ($backImagePath) {
                \Log::info('Back image stored successfully', [
                    'user_id' => $user->id,
                    'path' => $backImagePath,
                    'original_name' => $request->file('id_back_image')->getClientOriginalName(),
                    'size' => $request->file('id_back_image')->getSize()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to store back image', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file_info' => $request->file('id_back_image') ? 'File exists' : 'No file'
            ]);
            return redirect()->back()
                ->with('error', 'Failed to upload back image: ' . $e->getMessage())
                ->withInput();
        }

        // Create validation record
        UserValidation::create([
            'user_id' => $user->id,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'id_front_image' => $frontImagePath,
            'id_back_image' => $backImagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('profile.show', $user->id)
            ->with('success', 'Your ID validation request has been submitted successfully. Please wait for admin approval.');
    }

    public function show($userId)
    {
        $validation = UserValidation::with('user')->where('user_id', $userId)->firstOrFail();
        
        return view('validation.show', compact('validation'));
    }

    public function approve(UserValidation $validation)
    {
        $validation->update([
            'status' => 'approved',
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'ID validation approved successfully.');
    }

    public function reject(Request $request, UserValidation $validation)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $validation->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'ID validation rejected.');
    }

    public function index()
    {
        $validations = UserValidation::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('validation.index', compact('validations'));
    }
}
