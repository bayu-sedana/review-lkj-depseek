<?php

namespace App\Http\Controllers;

use App\Models\Satker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SatkerController extends Controller
{
    /**
     * Display a listing of the satkers.
     */
    public function index(): View
    {
        $satkers = Satker::withCount('users')->orderBy('kode_satker')->paginate(15);

        return view('admin.satkers.index', compact('satkers'));
    }

    /**
     * Show the form for creating a new satker.
     */
    public function create(): View
    {
        return view('admin.satkers.create');
    }

    /**
     * Store a newly created satker in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_satker' => ['required', 'string', 'max:255', 'unique:satkers,kode_satker'],
            'nama_satker' => ['required', 'string', 'max:255'],
        ]);

        Satker::create($validated);

        return redirect()
            ->route('admin.satkers.index')
            ->with('success', 'Satker berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified satker.
     */
    public function edit(Satker $satker): View
    {
        return view('admin.satkers.edit', compact('satker'));
    }

    /**
     * Update the specified satker in storage.
     */
    public function update(Request $request, Satker $satker): RedirectResponse
    {
        $validated = $request->validate([
            'kode_satker' => ['required', 'string', 'max:255', 'unique:satkers,kode_satker,'.$satker->id],
            'nama_satker' => ['required', 'string', 'max:255'],
        ]);

        $satker->update($validated);

        return redirect()
            ->route('admin.satkers.index')
            ->with('success', 'Satker berhasil diperbarui.');
    }

    /**
     * Remove the specified satker from storage.
     */
    public function destroy(Satker $satker): RedirectResponse
    {
        if ($satker->users()->exists()) {
            return redirect()
                ->route('admin.satkers.index')
                ->with('error', 'Satker tidak dapat dihapus karena masih memiliki user terkait.');
        }

        $satker->delete();

        return redirect()
            ->route('admin.satkers.index')
            ->with('success', 'Satker berhasil dihapus.');
    }
}
