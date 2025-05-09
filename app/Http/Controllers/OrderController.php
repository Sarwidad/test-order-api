<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Array untuk menyimpan order sementara di memori
    private $orders = []; 
    private $nextId = 1;  

    // Fungsi untuk menerima dan menyimpan order
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'menu_item'     => 'required|string',
            'quantity'      => 'required|integer|min:1',
        ]);

        // Menyimpan order baru
        $order = [
            'order_id'      => $this->nextId++,
            'customer_name' => $validated['customer_name'],
            'menu_item'     => $validated['menu_item'],
            'quantity'      => $validated['quantity'],
        ];

        // Menyimpan order dalam array
        $this->orders[] = $order;

        // Mengembalikan response sukses dengan ID order
        return response()->json([
            'message'   => 'Order received successfully',
            'order_id'  => $order['order_id'],
        ], 201); 
    }

    // Fungsi untuk menampilkan semua order atau filter berdasarkan customer_name
    public function index(Request $request)
    {
        $customerName = $request->query('customer_name');

        $filteredOrders = $this->orders; 

        if ($customerName) {
            // Filter berdasarkan nama customer (case-insensitive)
            $filteredOrders = array_filter($this->orders, function ($order) use ($customerName) {
                return stripos($order['customer_name'], $customerName) !== false;
            });
        }

        // Mengembalikan daftar order yang difilter
        return response()->json(array_values($filteredOrders));
    }
}


