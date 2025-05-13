@props(['model', 'class' => 'mt-4 pt-4 border-t border-gray-200'])

<div class="{{ $class }}">
    <div class="flex flex-col space-y-1">
        <p class="text-xs text-gray-500 flex items-center">
            <i class="fas fa-clock mr-1.5"></i>
            <span><strong>Created:</strong> {{ $model->created_at->setTimezone('Asia/Singapore')->format('F j, Y \a\t g:i a') }} (GMT+8)</span>
        </p>
        <p class="text-xs text-gray-500 flex items-center">
            <i class="fas fa-history mr-1.5"></i>
            <span><strong>Last updated:</strong> {{ $model->updated_at->setTimezone('Asia/Singapore')->format('F j, Y \a\t g:i a') }} (GMT+8)</span>
        </p>
    </div>
</div> 