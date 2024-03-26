<?php

namespace App\Http\Controllers;

use App\Models\ChMessage;
use Illuminate\Http\Request;
use App\Events\UnreadMessagesUpdated;

class ChatifyController extends Controller
{
    public function showChatify()
    {
        if (auth()->user()) {
            session()->put('chat_with', auth()->user()->id);
            $id = auth()->user()->id; // Assign the user ID to the $id variable
            $messengerColor = '#2180f3'; // Set a default color, or retrieve the color from your configuration
            $dark_mode = true; // Set the dark mode variable based on your logic
        }

        return view('vendor.Chatify.pages.app', compact('id', 'messengerColor', 'dark_mode'));
    }


    public function updateUnreadMessagesCount()
    {
        // Update the unread count
        $unreadCount = ChMessage::where('to_id', auth()->user()->id)->where('seen', 0)->count();



        return response()->json(['unreadCount' => $unreadCount]);
    }
}
