<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download($orderId)
    {
        $order = Order::with(['orderItems.menuItem.restaurant', 'user'])->findOrFail($orderId);
        
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Generate PDF using simple HTML to PDF conversion
        $html = view('invoice.pdf', compact('order'))->render();
        
        // Create PDF using DOMPDF
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        // Download the PDF
        return $pdf->stream("invoice_{$order->id}.pdf");
    }
}
