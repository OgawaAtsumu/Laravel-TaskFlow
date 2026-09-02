<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,

            'status' => $this->status,
            'status_label' => match ($this->status) {
                'not_started' => '未着手',
                'in_progress' => '進行中',
                'completed' => '完了',
                default => '不明',
            },

            'priority' => $this->priority,
            'priority_label' => match ($this->priority) {
                'low' => '低',
                'medium' => '中',
                'high' => '高',
                default => '不明',
            },

            'due_date' => $this->due_date?->format('Y-m-d'),
            'completed_at' => $this->completed_at?->format('Y-m-d H:i'),

            'is_overdue' => $this->due_date !== null
                && $this->status !== 'completed'
                && $this->due_date->isBefore(today()),

            'category' => $this->category
                ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ]
                : null,

            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
