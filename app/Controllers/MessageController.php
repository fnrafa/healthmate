<?php

namespace App\Controllers;

use App\Models\Message;
use App\Providers\Notification;
use App\Providers\Request;
use JetBrains\PhpStorm\NoReturn;

class MessageController
{
    #[NoReturn] public function sendMessage(Request $request): void
    {
        if (!$request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'message' => 'string',
            'media' => 'nullable|file'
        ])) {
            response(['success' => false, 'errors' => $request->getErrors()]);
        }

        $messageData = [
            'consultation_id' => $request->consultation_id,
            'sender_id' => auth()->id,
            'message' => $request->message,
        ];

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $fileName = time() . '_' . $file['name'];
            $targetPath = __DIR__ . '/../../public/media/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $messageData['media'] = '/media/' . $fileName;
            } else {
                response(['success' => false, 'errors' => ['Failed to upload media file.']]);
            }
        }

        $message = Message::create($messageData);

        (new Notification)->sendToUser($message->sender_id, [
            'type' => 'chat',
            'userId' => $message->sender_id,
            'message' => $message->message,
            'consultation_id' => $message->consultation_id,
            'media' => $message->media,
            'created_at' => $message->created_at
        ]);

        response(['success' => true, 'message' => $message->message, 'message_id' => $message->id, 'created_at' => $message->created_at, 'media' => $message->media]);
    }

    #[NoReturn] public function updateMessageStatus(Request $request): void
    {
        if (!$request->validate([
            'message_id' => 'required|exists:messages,id',
            'is_read' => 'required|boolean'
        ])) {
            response(['success' => false, 'errors' => $request->getErrors()]);
        }

        $message = Message::find($request->message_id);
        $message->is_read = $request->is_read;
        $message->save();

        (new Notification)->sendToUser($message->sender_id, [
            'type' => 'mark_read',
            'userId' => $message->sender_id,
            'message' => $message->message,
            'consultation_id' => $message->consultation_id,
            'media' => $message->media,
            'created_at' => $message->created_at
        ]);

        response(['success' => true, 'is_read' => $message->is_read]);
    }
}
