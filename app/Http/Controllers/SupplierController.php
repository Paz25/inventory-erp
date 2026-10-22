<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $search = $request->input('search');

    //     $suppliers = Supplier::query()
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($query) use ($search) {
    //                 $query->where('supplier_name', 'like', "%{$search}%")
    //                     ->orWhere('pic_name', 'like', "%{$search}%");
    //             });
    //         })
    //         ->latest()
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view('suppliers.index', compact('suppliers', 'search'));
    // }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = DB::table('suppliers')->whereNull('deleted_at')->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('supplier_name', 'like', "%{$search}%")
                    ->orWhere('pic_name', 'like', "%{$search}%");
            });
        })->latest()->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        try {
            // Supplier::create($request->supplierData());
            DB::table('suppliers')->insert([
                'id' => (string) Str::uuid(),
                ...$request->supplierData(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('suppliers')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', 'Supplier gagal disimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encryptedId): JsonResponse
    {
        try {
            // $supplier = Supplier::findOrFail(Crypt::decrypt($encryptedId));
            $supplier = DB::table('suppliers')->whereNull('deleted_at')->find(Crypt::decrypt($encryptedId));

            if ($supplier === null) throw new ModelNotFoundException();

            return response()->json([
                'id' => $supplier->id,
                'nama_perusahaan' => $supplier->supplier_name,
                'alamat_perusahaan' => $supplier->supplier_address,
                'nama_pic' => $supplier->pic_name,
                'no_telp_pic' => $supplier->pic_contact_number,
                'email_pic' => $supplier->pic_contact_email,
                'update_url' => route('suppliers.update', $encryptedId),
            ]);
        } catch (DecryptException | ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Supplier tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, string $encryptedId)
    {
        try {
            // $supplier = Supplier::findOrFail(Crypt::decrypt($encryptedId));
            $supplier = DB::table('suppliers')->whereNull('deleted_at')->find(Crypt::decrypt($encryptedId));

            if ($supplier === null) throw new ModelNotFoundException();

            // $supplier->update($request->supplierData());
            DB::table('suppliers')->where('id', Crypt::decrypt($encryptedId))->update([
                ...$request->supplierData(),
                'updated_at' => now()
            ]);

            return redirect()->route('suppliers')->with('success', 'Supplier berhasil diperbarui.');
        } catch (DecryptException | ModelNotFoundException $e) {
            return redirect()->route('suppliers')->with('error', 'Supplier tidak ditemukan.');
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', 'Supplier gagal diperbarui.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        try {
            // $supplier = Supplier::findOrFail(Crypt::decrypt($encryptedId));
            $supplier = DB::table('suppliers')->whereNull('deleted_at')->find(Crypt::decrypt($encryptedId));

            if ($supplier === null) throw new ModelNotFoundException();

            // $supplier->delete();
            DB::table('suppliers')->where('id', Crypt::decrypt($encryptedId))->update([
                'deleted_at' => now()
            ]);

            return redirect()->route('suppliers')->with('success', 'Supplier berhasil dihapus.');
        } catch (DecryptException | ModelNotFoundException $e) {
            return redirect()->route('suppliers')->with('error', 'Supplier tidak ditemukan.');
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('suppliers')->with('error', 'Supplier tidak ditemukan.');
        }
    }
}
