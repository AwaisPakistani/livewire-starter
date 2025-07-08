<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
class AuthenticatedSessionController extends Controller
{
    public function create(){
        dd('create');
    }
    public function store(LoginRequest $request): RedirectResponse
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        // return response()->json(['login successfully'],200);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

         if (Auth::attempt($credentials)) {
            $user = User::where('email',$request->email)->first();
            // if (! $token = Auth::attempt($credentials, ['exp' => Carbon::now()->addDays(365)->timestamp])) {
            
            //     return redirect()->back()->with('error', 'Unauthorized!');

            // }
            // $roles = $user->getRoleNames(); 
            // $permissions = $user->getAllPermissions();

            // $token = $user->createToken('cdigital_accounts')->plainTextToken;
            // return response()->json([
            //     'token'=>$token,
            //     'user'=>$user,
            //     'roles'=>$roles,
            //     'permissionss'=>$permissions,
            // ],200);
            return redirect()->route('posts')->with('success', 'Logged in successfully!');
        }

        return redirect()->back()->with('error', 'credentials does not match!');
    }

    public function getUser(Request $request){
        // $user = $request->user()->with('company');
        $user = $request->user();
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions();
        
        if($user && $user->company_id){
            $globalSetting = GlobalSetting::where('company_id', $user->company_id)->first();
            $currentDate = now();
            $dateBeforeAccountingDays = $currentDate->copy()->subDays($globalSetting->accounting_days);
        }
        
        
        // Transform permissions into abilityRules
        if($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('Company Admin')){
            $abilityRules = [
                [
                    'action' => 'manage',
                    'subject' => 'all',
                ],
                
            ];
        }else{
            $abilityRules = collect($permissions)->map(function ($permission) {
                $parts = explode('-', $permission->name, 2);
                return [
                    'action' => $parts[0] ?? '',
                    'subject' => ucfirst($parts[1] ?? ''),
                ];
            })->toArray();
        }
        $user->globalSetting = $globalSetting;
        $user->dateBeforeAccountingDays = $dateBeforeAccountingDays;
       
        // $abilityRules = [
        //     [
        //         'action' => 'manage',
        //         'subject' => 'all',
        //     ],
            
        // ];

        
        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'companyData'=>$user->company,
            'abilityRules' => $abilityRules,
        ], 200);
        

    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email',$request->email)->first();
            if (! $token = Auth::attempt($credentials, ['exp' => Carbon::now()->addDays(365)->timestamp])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $roles = $user->getRoleNames(); 
            $permissions = $user->getAllPermissions();

            $token = $user->createToken('cdigital_accounts')->plainTextToken;
            return response()->json([
                'token'=>$token,
                'user'=>$user,
                'roles'=>$roles,
                'permissionss'=>$permissions,
            ],200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        // return redirect('/');

        // Vue 
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
