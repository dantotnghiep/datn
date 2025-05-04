<?php

namespace App\Http\Controllers\Admin;

use App\Events\InventoryReceiptStatusChanged;
use App\Models\InventoryReceipt;
use App\Models\InventoryReceiptItem;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryReceiptController extends BaseController
{
    public function __construct()
    {
        $this->model = InventoryReceipt::class;
        $this->viewPath = 'admin.components.inventory-receipts';
        $this->route = 'admin.inventory-receipts';
        parent::__construct();
    }
    
    // Override create method to add product variations data
    public function create()
    {
        $fields = $this->model::getFields();
        $productVariations = ProductVariation::with('product')->get()
            ->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'name' => ($variation->product ? $variation->product->name : 'Unknown') . ' - ' . $variation->name,
                    'price' => $variation->price,
                    'stock' => $variation->stock
                ];
            });
            
        return view($this->viewPath . '.form', [
            'fields' => $fields,
            'route' => $this->route,
            'productVariations' => $productVariations
        ]);
    }
    
    // Override store method to handle inventory receipt items
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validate main receipt data
            $validated = $request->validate($this->model::rules());
            
            // Set user_id if not provided
            if (!isset($validated['user_id'])) {
                $validated['user_id'] = auth()->id();
            }
            // Set default status to pending
            $validated['status'] = 'pending';
            // Create receipt
            $receipt = $this->model::create($validated);
            
            // Handle receipt items
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    if (empty($item['product_variation_id']) || empty($item['quantity']) || empty($item['unit_cost'])) {
                        continue; // Skip invalid items
                    }
                    
                    // Calculate subtotal
                    $subtotal = $item['quantity'] * $item['unit_cost'];
                    
                    // Create receipt item
                    InventoryReceiptItem::create([
                        'inventory_receipt_id' => $receipt->id,
                        'product_variation_id' => $item['product_variation_id'],
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'subtotal' => $subtotal
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route($this->route . '.index')
                ->with('success', 'Inventory receipt created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating inventory receipt: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Override edit method to load receipt items
    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $fields = $this->model::getFields();
        
        $productVariations = ProductVariation::with('product')->get()
            ->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'name' => ($variation->product ? $variation->product->name : 'Unknown') . ' - ' . $variation->name,
                    'price' => $variation->price,
                    'stock' => $variation->stock
                ];
            });
            
        $receiptItems = InventoryReceiptItem::where('inventory_receipt_id', $id)
            ->with('productVariation.product')
            ->get();
            
        return view($this->viewPath . '.form', [
            'item' => $item,
            'fields' => $fields,
            'route' => $this->route,
            'productVariations' => $productVariations,
            'receiptItems' => $receiptItems
        ]);
    }
    
    // Override update method to handle inventory receipt items
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $item = $this->model::findOrFail($id);
            $validated = $request->validate($this->model::rules($id));
            
            // Preserve the status and created_at values
            $oldStatus = $item->status;
            $oldCreatedAt = $item->created_at;
            
            // Update receipt
            $item->update($validated);
            
            // Restore status and created_at
            $item->status = $oldStatus;
            $item->created_at = $oldCreatedAt;
            $item->save();
            
            // Get existing items to calculate stock adjustments
            $existingItems = InventoryReceiptItem::where('inventory_receipt_id', $id)->get()
                ->keyBy('id')
                ->toArray();
                
            // Reverse stock changes for all existing items
            foreach ($existingItems as $existingItem) {
                $variation = ProductVariation::find($existingItem['product_variation_id']);
                if ($variation) {
                    $variation->stock -= $existingItem['quantity'];
                    $variation->save();
                }
            }
            
            // Delete all existing items
            InventoryReceiptItem::where('inventory_receipt_id', $id)->delete();
            
            // Add new items
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    if (empty($item['product_variation_id']) || empty($item['quantity']) || empty($item['unit_cost'])) {
                        continue; // Skip invalid items
                    }
                    
                    // Calculate subtotal
                    $subtotal = $item['quantity'] * $item['unit_cost'];
                    
                    // Create receipt item
                    InventoryReceiptItem::create([
                        'inventory_receipt_id' => $id,
                        'product_variation_id' => $item['product_variation_id'],
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'subtotal' => $subtotal
                    ]);
                    
                    // Update product variation stock
                    $variation = ProductVariation::find($item['product_variation_id']);
                    if ($variation) {
                        $variation->stock += $item['quantity'];
                        $variation->save();
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route($this->route . '.index')
                ->with('success', 'Inventory receipt updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating inventory receipt: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update the status of an inventory receipt
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,completed,cancelled'
            ]);
            
            $receipt = $this->model::findOrFail($id);
            $oldStatus = $receipt->status;
            $newStatus = $request->status;
            
            // Update the receipt status
            $receipt->status = $newStatus;
            $receipt->save();
            
            // If changing from pending to completed, and it wasn't already completed before
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                // Add stock for all items in the receipt
                $receiptItems = InventoryReceiptItem::where('inventory_receipt_id', $id)->get();
                
                foreach ($receiptItems as $item) {
                    $variation = ProductVariation::find($item->product_variation_id);
                    if ($variation) {
                        $variation->stock += $item->quantity;
                        $variation->save();
                    }
                }
            }
            
            // If changing from completed to another status, and it was completed before
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                // Subtract stock for all items in the receipt
                $receiptItems = InventoryReceiptItem::where('inventory_receipt_id', $id)->get();
                
                foreach ($receiptItems as $item) {
                    $variation = ProductVariation::find($item->product_variation_id);
                    if ($variation) {
                        $variation->stock -= $item->quantity;
                        $variation->save();
                    }
                }
            }
            
            // Dispatch event for real-time updates
            event(new InventoryReceiptStatusChanged($receipt));
            
            return redirect()->route($this->route . '.index')
                ->with('success', 'Inventory receipt status updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating inventory receipt status: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receipt = $this->model::with(['items.productVariation.product', 'user'])->findOrFail($id);
        
        return view($this->viewPath . '.details', [
            'receipt' => $receipt,
            'title' => 'Chi tiết phiếu nhập kho #' . $receipt->receipt_number,
            'route' => $this->route
        ]);
    }
} 