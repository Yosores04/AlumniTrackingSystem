@extends('layouts.instructor')

@section('title', 'Generate Alumni Report')

@section('content')
<div class="content-card">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Generate Alumni Report</h1>
        <p class="text-gray-600">Configure your report settings below.</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-xl">
        <form action="{{ route('instructor.alumni.report') }}" method="GET" target="_blank">
            <div class="mb-6">
                <label for="title" class="form-label">
                    Report Title
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    class="form-control" 
                    value="Alumni Report"
                >
                <p class="mt-1 text-xs text-gray-500">
                    Title that will appear on your report.
                </p>
            </div>
            
            <div class="mb-6">
                <label for="orientation" class="form-label">
                    Page Orientation
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative border border-gray-300 rounded-md p-4 flex flex-col items-center hover:border-primary cursor-pointer transition-all">
                        <input 
                            type="radio" 
                            name="orientation" 
                            id="orientation-landscape" 
                            value="L" 
                            class="sr-only" 
                            checked
                        >
                        <label for="orientation-landscape" class="cursor-pointer flex flex-col items-center w-full">
                            <div class="w-24 h-16 border border-gray-300 mb-4 flex items-center justify-center">
                                <div class="text-gray-400 text-xs">Landscape</div>
                            </div>
                            <span class="text-sm font-medium">Landscape</span>
                            <span class="text-xs text-gray-500 mt-1">Better for tables</span>
                        </label>
                    </div>
                    <div class="relative border border-gray-300 rounded-md p-4 flex flex-col items-center hover:border-primary cursor-pointer transition-all">
                        <input 
                            type="radio" 
                            name="orientation" 
                            id="orientation-portrait" 
                            value="P" 
                            class="sr-only"
                        >
                        <label for="orientation-portrait" class="cursor-pointer flex flex-col items-center w-full">
                            <div class="w-16 h-24 border border-gray-300 mb-4 flex items-center justify-center">
                                <div class="text-gray-400 text-xs">Portrait</div>
                            </div>
                            <span class="text-sm font-medium">Portrait</span>
                            <span class="text-xs text-gray-500 mt-1">Better for profiles</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3 mt-8">
                <a href="{{ route('instructor.alumni.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Generate PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioInputs = document.querySelectorAll('input[type="radio"][name="orientation"]');
        const radioContainers = document.querySelectorAll('.relative.border.rounded-md');
        
        // Function to update selected state
        function updateSelected() {
            radioContainers.forEach((container, index) => {
                if (radioInputs[index].checked) {
                    container.classList.add('border-primary', 'bg-primary-5');
                } else {
                    container.classList.remove('border-primary', 'bg-primary-5');
                }
            });
        }
        
        // Initialize
        updateSelected();
        
        // Add click handlers to the containers
        radioContainers.forEach((container, index) => {
            container.addEventListener('click', function() {
                radioInputs[index].checked = true;
                updateSelected();
            });
        });
    });
</script>
@endpush 