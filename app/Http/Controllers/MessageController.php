<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;
use App\Events\CallInitiated;

class MessageController extends Controller
{
    // Afficher toutes les conversations de l'utilisateur
    public function index()
    {
        $userId = Auth::id();
        
        // Récupère les utilisateurs avec qui j'ai échangé des messages
        $allMessages = Message::where(function ($query) use ($userId) {
                $query->where('from_id', $userId)
                      ->orWhere('to_id', $userId);
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'desc')
            ->get();
        $grouped = $allMessages->groupBy(function ($message) use ($userId) {
            return $message->from_id == $userId ? $message->to_id : $message->from_id;
        });
        // Pagination manuelle sur les conversations (20 par page)
        $page = request()->get('page', 1);
        $perPage = 20;
        $conversations = $grouped->slice(($page - 1) * $perPage, $perPage);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $conversations,
            $grouped->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        // Récupère aussi la liste des amis pour permettre de démarrer de nouvelles conversations
        $user = Auth::user();
        $friends = $user->allFriends();
        return view('messages.index', ['conversations' => $paginated, 'friends' => $friends]);
    }

    // Afficher une conversation avec un utilisateur
    public function show($user_id)
    {
        $me = Auth::user();
        $other = User::findOrFail($user_id);

        // Sécurité : ne voir que les conversations avec ses amis
        if (!$me->allFriends()->contains('id', $user_id)) {
            abort(403, 'Vous ne pouvez voir que les conversations avec vos amis.');
        }

        // Récupère tous les messages entre moi et l'autre utilisateur
        $messages = Message::where(function ($q) use ($me, $user_id) {
            $q->where('from_id', $me->id)->where('to_id', $user_id);
        })->orWhere(function ($q) use ($me, $user_id) {
            $q->where('from_id', $user_id)->where('to_id', $me->id);
        })->orderBy('created_at', 'asc')->paginate(20);

        return view('messages.show', compact('other', 'messages'));
    }

    // Envoyer un message
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'content' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,mp4,webm,ogg,pdf,doc,docx,xls,xlsx,txt',
            'audio' => 'nullable|file|max:10240|mimes:mp3,wav,ogg,webm',
        ]);

        $me = Auth::user();
        if ($user_id == $me->id) {
            abort(403, 'Vous ne pouvez pas vous envoyer de message à vous-même.');
        }
        if (!$me->allFriends()->contains('id', $user_id)) {
            abort(403, 'Vous ne pouvez envoyer un message qu’à vos amis.');
        }

        $attachmentPath = null;
        $audioPath = null;
        $type = 'text';

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages', 'public');
            $mime = $request->file('attachment')->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $type = 'video';
            } elseif (in_array($mime, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain'])) {
                $type = 'file';
            } else {
                $type = 'file';
            }
        }

        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('messages/audio', 'public');
            $type = 'audio';
        }

        if ($type === 'text' && empty($request->content)) {
            return back()->with('error', 'Le message ne peut pas être vide.');
        }

        $msg = Message::create([
            'from_id' => $me->id,
            'to_id' => $user_id,
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
            'audio_path' => $audioPath,
            'is_read' => false,
            'type' => $type,
        ]);

        // Notification
        $recipient = User::find($user_id);
        if ($recipient) {
            $recipient->notify(new NewMessageNotification($me, $msg));
        }

        // Retourner une réponse JSON si c'est une requête AJAX
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'message_id' => $msg->id,
                'content' => $msg->content,
                'type' => $msg->type,
                'created_at' => $msg->created_at
            ]);
        }

        return redirect()->route('messages.show', $user_id);
    }

    public function initiateCall(Request $request)
    {
        $request->validate([
            'to_id' => 'required|exists:users,id',
            'type' => 'required|in:audio,video',
        ]);
        $from = Auth::user();
        $to = \App\Models\User::findOrFail($request->to_id);
        broadcast(new CallInitiated($from, $to, $request->type))->toOthers();
        return response()->json(['status' => 'ok']);
    }

    public function deleteForMe($id)
    {
        $msg = Message::findOrFail($id);
        $user = Auth::user();
        // Ne pas dupliquer
        if (!$msg->deletions()->where('user_id', $user->id)->exists()) {
            $msg->deletions()->attach($user->id);
        }
        return back()->with('success', 'Message supprimé pour vous.');
    }

    public function destroy($id)
    {
        $msg = Message::findOrFail($id);
        if ($msg->from_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez supprimer que vos propres messages.');
        }
        // Supprimer le fichier joint si présent
        if ($msg->attachment_path && \Storage::disk('public')->exists($msg->attachment_path)) {
            \Storage::disk('public')->delete($msg->attachment_path);
        }
        $msg->delete();
        return back()->with('success', 'Message supprimé.');
    }
}
