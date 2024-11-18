<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Design;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Storage;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;
use App\Notifications\FeedbackMail;
class MessageController extends Controller
{
    public function getUnreadCount()
    {
        $user = Auth::user();
        
        $unreadThreads = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->select('sender_id')
            ->distinct()
            ->count();

        return response()->json(['count' => $unreadThreads]);
    }

    public function index()
    {
        $user = Auth::user();
        $messages = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            });

        // Sort conversations by the latest message
        $messages = $messages->sortByDesc(function ($conversation) {
            return $conversation->max('created_at');
        });

        $unreadThreads = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->select('sender_id')
            ->distinct()
            ->count();
            
            $user->last_seen = Carbon::now();
            $user->save();

            foreach ($messages as $userId => $conversation) {
                $lastMessage = $conversation->first();
                $otherUser = $userId == $lastMessage->sender_id ? $lastMessage->sender : $lastMessage->receiver;
                $messages[$userId] = [
                    'user' => $otherUser,
                    'last_message' => Str::limit($lastMessage->content, 50),
                    'created_at' => $lastMessage->created_at,
                    'is_read' => $lastMessage->is_read,
                    'last_message_id' => $lastMessage->id,
                ];
            }

            return view('message_list', compact('messages', 'user', 'unreadThreads'));
    }

    public function getNewMessages(Request $request, $userId)
    {
        $user = Auth::user();
        $lastTimestamp = Carbon::parse($request->input('last_timestamp'));

        $counterKey = 'new_messages_counter_' . $user->id;
        $limit = 20;
        $totalLimit = 30;
        $delay = 250;

        if (!Cache::has($counterKey)) {
            Cache::add($counterKey, 0, now()->addHours(1));
        } else {
            Cache::increment($counterKey);
            if (Cache::get($counterKey) >= $totalLimit) {
                return response()->json(['error' => 'Too many requests'], 429);
            }
            if (Cache::get($counterKey) >= $limit) {
                sleep($delay);
            }
        }

        \Log::info('Fetching new messages', [
            'user_id' => $user->id,
            'other_user_id' => $userId,
            'last_timestamp' => $lastTimestamp->toDateTimeString()
        ]);

        $messages = Message::where(function($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($user, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->where('created_at', '>', $lastTimestamp)
          ->with(['project', 'sender', 'attachments'])
          ->orderBy('created_at', 'asc')
          ->get();

        \Log::info('Found messages', ['count' => $messages->count()]);

        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'created_at' => $message->created_at->toDateTimeString(),
                'is_read' => $message->is_read,
                'project' => $message->project ? [
                    'id' => $message->project->id,
                    'title' => $message->project->title,
                    'link' => '../project/'.$message->project->id,
                    'filepath' => $message->project->filepath,
                ] : null,
                'attachments' => $message->attachments->map(function ($attachment) {
                    return [
                        'filename' => $attachment->filename,
                        'url' => Storage::url($attachment->path),
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                    ];
                }),
            ];
        });

        return response()->json(['messages' => $formattedMessages]);
    }

    public function getConversation($userId)
    {
        $user = Auth::user();
        $otherUser = User::findOrFail($userId);
        $messages = Message::where(function($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($user, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->with('project')->orderBy('created_at', 'asc')->get();

        $formattedMessages = $messages->map(function ($message) {
            $attachments = $message->attachments->map(function ($attachment) {
                return [
                    'filename' => $attachment->filename,
                    'url' => 'storage/message_attachments/' . pathinfo($attachment->path)['basename'],
                    'mime_type' => $attachment->mime_type,
                    'size' => $attachment->size,
                ];
            });

            $projectInfo = null;
            if ($message->project) {
                $design = Design::findOrFail($message->project->design_id);
                $projectInfo = [
                    'title' => $design->title,
                    'link' => "../project/" . $design->id,
                    'filepath' => $message->project->filepath,
                ];
            }

            return [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'created_at' => $message->created_at,
                'is_read' => $message->is_read,
                'sender_name' => $message->sender->name,
                'project' => $projectInfo,
                'attachments' => $attachments,
            ];
        });

        return response()->json([
            'messages' => $formattedMessages,
            'otherUser' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'avatar' => $otherUser->avatar,
                'last_seen' => $otherUser->last_seen
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:xls,xlsx,pdf|max:500', // 500 KB max
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('message_attachments', 'public');
            $publicPath = public_path($path);
            
            MessageAttachment::create([
                'message_id' => $message->id,
                'filename' => $file->getClientOriginalName(),
                'path' => $publicPath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return response()->json($message->load('attachments'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        if ($request->receiver_id == 7) {
            $user = User::find(7);
            $sender = User::find(Auth::id());
            $user->notify(new FeedbackMail(
                $sender->name,
                $sender->email,
                $request->content
            ));
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content
        ]);

        

        return response()->json($message);
    }

    /**
     * Create a system message related to a project.
     *
     * @param Project $project
     * @param User $receiver
     * @param string $content
     * @return Message
     */
    public function createProjectMessage(Project $project, User $receiver, string $content)
    {
        return Message::create([
            'sender_id' => User::where('id', 7)->firstOrFail()->id, // Assuming 500 is the system user ID
            'receiver_id' => $receiver->id,
            'content' => $content,
            'project_id' => $project->id,
        ]);
    }

    public function markAsRead($messageId)
    {
        $message = Message::findOrFail($messageId);
        $message->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function archiveMessage($messageId)
    {
        $message = Message::findOrFail($messageId);
        $message->update(['is_archived' => true]);

        return response()->json(['success' => true]);
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $this->authorize('delete', $message);
        $message->delete();
        return response()->json(['success' => true]);
    }

    public function markAsUnread($id)
    {
        $message = Message::findOrFail($id);
        $this->authorize('update', $message);
        $message->update(['is_read' => false]);
        return response()->json(['success' => true]);
    }

    public function checkNewMessages(Request $request)
    {
        $user = Auth::user();
        $lastChecked = $request->input('last_checked', now()->subMinutes(5));

        $newMessages = Message::where('receiver_id', $user->id)
            ->where('created_at', '>', $lastChecked)
            ->with('sender')
            ->get();

        $formattedMessages = $newMessages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'content' => Str::limit($message->content, 50),
                'created_at' => $message->created_at->diffForHumans(),
            ];
        });

        return response()->json($formattedMessages);
    }

    public function contactExecutor(Request $request, $projectId)
    {
        $request->validate([
            'executor_id' => 'required|exists:suppliers,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $executorId = $request->input('executor_id');
        $executor = Supplier::findOrFail($executorId);
        $executorUser = User::findOrFail($executor->user_id);
        $content = "Хотел узнать у вас что вы думаете о таком проекте и что можно с ним сделать";
        $project = Project::findOrFail($projectId);
        $message = Message::create([
            'sender_id' => $project->user_id,
            'receiver_id' => $executorUser->id,
            'content' => $content,
            'project_id' => $project->id,
        ]);

        return response()->json($message);
    }
}