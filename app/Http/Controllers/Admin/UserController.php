<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $title = 'Menghapus Akun!';
        $text = "Anda yakin ingin menghapus Akun?";
        confirmDelete($title, $text);

        return view('admin.users.index', ['users' => $users]);
    }

    // Menampilkan form upload CSV
    public function showBulkCreateForm()
    {
        return view('admin.users.bulk-create');
    }

    // Memproses file CSV dan menambahkan user
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        // Pastikan file memiliki header
        $header = array_shift($data);

        // Validasi header CSV
        if ($header !== ['name', 'email', 'password', 'role_as']) {
            return back()->withErrors(['csv_file' => 'Header file CSV harus: name, email, password, role_as']);
        }

        $usersToInsert = [];
        foreach ($data as $row) {
            $validator = Validator::make([
                'name' => $row[0],
                'email' => $row[1],
                'password' => $row[2],
                'role_as' => $row[3],
            ], [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:4',
                'role_as' => 'required|in:0,1,2',
            ]);

            if ($validator->fails()) {
                return back()->withErrors(['csv_file' => 'Error pada baris: ' . implode(', ', $row)]);
            }

            $usersToInsert[] = [
                'name' => $row[0],
                'email' => $row[1],
                'password' => Hash::make($row[2]),
                'role_as' => $row[3],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert batch
        User::insert($usersToInsert);

        return redirect('/admin/users')->with('success', 'User berhasil ditambahkan secara serentak!');
    }

    public function downloadTemplate()
    {
        // Define header file CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_users.csv"',
        ];

        // Data template CSV
        $csvData = [
            ['name', 'email', 'password', 'role'], // Header
            ['John Doe', 'john.doe@example.com', 'password123', '0'],
            ['Jane Smith', 'jane.smith@example.com', 'password456', '1'],
        ];

        // Convert data ke format CSV
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        // Return response untuk mengunduh CSV
        return Response::stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
            'role_as' => 'required|in:0,1,2',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role_as' => $validatedData['role_as'],
        ]);

        return redirect('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:4|confirmed',
            'role_as' => 'required|in:0,1,2',
        ]);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_as' => $validatedData['role_as'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validatedData['password'])]);
        }

        return redirect('/admin/users')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user) {
        $user->delete();
        alert()->success('Hore!', 'Akun Berhasil Dihapus.');
        return back();
    }
}
