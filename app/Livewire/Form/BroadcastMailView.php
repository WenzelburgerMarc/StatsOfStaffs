<?php

namespace App\Livewire\Form;

use App\Models\User;
use App\Notifications\BroadCastMailNotification;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class BroadcastMailView extends Component
{
    use WithFileUploads;

    public $selectedRole = 0;

    public $subject;

    public $message;

    public $attachments = [];

    public function render()
    {
        return view('components.livewire.form.broadcast-mail-view');
    }

    public function mount()
    {
        $this->subject = old('subject');
        $this->message = old('message');
    }

    #[On('broadcastMailRoleSelected')]
    public function setSelectedRole($role)
    {
        $this->selectedRole = $role;

    }

    public function sendBroadcastMail()
    {

        $emails = $this->getAllEmailsByRole($this->selectedRole);

        $this->validate([
            'subject' => 'required',
            'message' => 'required',
            'attachments.*' => 'nullable|file|max:2048',
        ]);

        if (empty($emails)) {

            $this->dispatch('sendFlashMessage', type: 'info', message: 'No emails found - please check the selected Role.');

        } else {
            try {
                $mail = new BroadCastMailNotification($this->subject, $this->message);

                if (! empty($this->attachments)) {
                    foreach ($this->attachments as $attachment) {
                        $fileContents = $attachment->get();
                        $fileName = $attachment->getClientOriginalName();
                        $mimeType = $attachment->getMimeType();

                        $mail->attachData($fileContents, $fileName, [
                            'mime' => $mimeType,
                            'encoding' => 'base64',
                        ]);
                    }
                }
                \Mail::to($emails)->send($mail);
                $this->dispatch('sendFlashMessage', type: 'success', message: 'Mail sent successfully.');
                $this->reset();
            } catch (\Error $e) {
                $this->dispatch('sendFlashMessage', type: 'error', message: 'An error occurred.');
            }

        }

    }

    private function getAllEmailsByRole($role)
    {

        if ($role !== 0 && isset($role)) {
            return User::where('role_id', $role)->pluck('email')->toArray();
        }

        return User::pluck('email')->toArray();
    }
}
