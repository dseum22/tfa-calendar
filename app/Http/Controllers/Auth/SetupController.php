<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SetupController extends Controller
{

    public function index()
    {
        $role = User::find(Auth::user()->id)->role();
        if (!DB::table($role . 's')->where('user_id', Auth::user()->id)->exists()) {
            return view('auth.setup');
        } else {
            return redirect()->route('dashboard');
        }
    }

    protected function create(Request $request)
    {
        if (User::find(Auth::user()->id)->role() == 'tutor') {
            $request->validate([
                'meeting_link' => 'required|url',
                'bio' => 'required|max:1000',
                'subject' => 'required'
            ]);
            Tutor::create([
                'user_id' => Auth::user()->id,
                'meeting_link' => $request->meeting_link,
                'bio' => $request->bio,
                'subjects' => json_encode(['subjects' => [intval($request->subject)]])
            ]);
        } else if (User::find(Auth::user()->id)->role() == 'student') {
            $request->validate([
                'terms' => 'required|accepted'
            ]);
            Student::create([
                'user_id' => Auth::user()->id,
                'terms' => true
            ]);
        }
        return redirect()->route('dashboard');
    }
}
