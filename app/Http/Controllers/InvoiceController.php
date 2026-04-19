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

        // Store PDF in session for raw download
        session(['invoice_pdf_' . $order->id => $pdf->output()]);
        
        // Return download page that will trigger download and redirect
        return view('invoice.download', compact('order'));
    }
    
    public function rawDownload($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Get PDF from session
        $pdfData = session('invoice_pdf_' . $orderId);
        
        if (!$pdfData) {
            // If not in session, regenerate PDF
            $html = view('invoice.pdf', compact('order'))->render();
            $pdf = new \Dompdf\Dompdf();
            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            $pdfData = $pdf->output();
        }
        
        // Clear session data
        session()->forget('invoice_pdf_' . $orderId);
        
        // Return PDF download
        return response($pdfData, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice_' . $order->id . '.pdf"',
            'Content-Length' => strlen($pdfData),
            'Cache-Control' => 'private, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
            'Expires' => '0'
        ]);
    }
}
