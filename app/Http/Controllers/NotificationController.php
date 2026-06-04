<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Customer;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('customer')->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        return view('notifications.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'title'       => 'required|string|max:255',
            'message'     => 'required|string',
            'type'        => 'required|in:bill_reminder,suspension_warning,promotion,general',
            'channel'     => 'required|in:sms,email,whatsapp',
        ]);

        Notification::create($request->all());

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully!');
    }

    public function edit(Notification $notification)
    {
        $customers = Customer::where('status', 'active')->get();
        return view('notifications.edit', compact('notification', 'customers'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'title'       => 'required|string|max:255',
            'message'     => 'required|string',
            'type'        => 'required|in:bill_reminder,suspension_warning,promotion,general',
            'channel'     => 'required|in:sms,email,whatsapp',
        ]);

        $notification->update($request->all());

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully!');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully!');
    }

    public function show(Notification $notification)
    {
        return redirect()->route('notifications.index');
    }
}