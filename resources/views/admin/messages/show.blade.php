@extends('admin.layouts.app')

@section('title', 'Message Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Message Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="message-detail">
                                <div class="message-header">
                                    <div class="sender-info">
                                        <h5>From: {{ $message->sender->name ?? 'Unknown' }}</h5>
                                        <p class="text-muted">{{ $message->sender->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="message-meta">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $message->created_at->format('M d, Y H:i A') }}
                                        </small>
                                        @if($message->is_read)
                                            <span class="badge badge-success">Read</span>
                                        @else
                                            <span class="badge badge-warning">Unread</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="message-content mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <p>{{ $message->content }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($message->conversation)
                                <div class="conversation-info mt-4">
                                    <h6>Conversation Details</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <p><strong>Conversation ID:</strong> {{ $message->conversation->id }}</p>
                                            <p><strong>Participants:</strong></p>
                                            <ul>
                                                <li>{{ $message->conversation->user1->name ?? 'Unknown' }} ({{ $message->conversation->user1->email ?? 'N/A' }})</li>
                                                <li>{{ $message->conversation->user2->name ?? 'Unknown' }} ({{ $message->conversation->user2->email ?? 'N/A' }})</li>
                                            </ul>
                                            <p><strong>Last Message:</strong> 
                                                @if($message->conversation->last_message_at)
                                                    @if(is_string($message->conversation->last_message_at))
                                                        {{ \Carbon\Carbon::parse($message->conversation->last_message_at)->format('M d, Y H:i A') }}
                                                    @else
                                                        {{ $message->conversation->last_message_at->format('M d, Y H:i A') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="message-actions">
                                <h6>Actions</h6>
                                <div class="btn-group-vertical d-grid gap-2">
                                    @if(!$message->is_read)
                                        <form action="{{ route('admin.messages.markAsRead', $message->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete Message
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-list"></i> View All Messages
                                    </a>
                                </div>
                            </div>
                            
                            <div class="message-status mt-4">
                                <h6>Message Status</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <p><strong>Delivery Status:</strong> {{ $message->delivery_status }}</p>
                                        <p><strong>Is Delivered:</strong> {{ $message->is_delivered ? 'Yes' : 'No' }}</p>
                                        <p><strong>Is Read:</strong> {{ $message->is_read ? 'Yes' : 'No' }}</p>
                                        @if($message->read_at)
                                            <p><strong>Read At:</strong> 
                                                @if(is_string($message->read_at))
                                                    {{ \Carbon\Carbon::parse($message->read_at)->format('M d, Y H:i A') }}
                                                @else
                                                    {{ $message->read_at->format('M d, Y H:i A') }}
                                                @endif
                                            </p>
                                        @endif
                                        <p><strong>Message Type:</strong> {{ $message->message_type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-refresh for real-time updates if needed
// setInterval(function() {
//     location.reload();
// }, 30000); // Refresh every 30 seconds
</script>
@endsection
