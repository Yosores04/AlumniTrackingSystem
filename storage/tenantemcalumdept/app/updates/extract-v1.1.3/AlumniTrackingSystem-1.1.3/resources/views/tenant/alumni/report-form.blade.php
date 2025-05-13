@extends('layouts.app')

@section('title', 'Generate Alumni Report')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Generate Alumni Report</h1>
        <p class="text-gray-600">Configure your report settings below.</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 max-w-xl">
        <form action="{{ route('alumni.report') }}" method="GET" target="_blank">
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Report Title
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                    value="Alumni Report"
                >
                <p class="mt-1 text-xs text-gray-500">
                    Title that will appear on your report.
                </p>
            </div>
            
            <div class="mb-6">
                <label for="orientation" class="block text-sm font-medium text-gray-700 mb-2">
                    Page Orientation
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative border border-gray-300 rounded-md p-4 flex flex-col items-center hover:border-blue-500 cursor-pointer transition-all">
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
                    <div class="relative border border-gray-300 rounded-md p-4 flex flex-col items-center hover:border-blue-500 cursor-pointer transition-all">
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
                <a href="{{ route('alumni.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
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
                    container.classList.add('border-blue-500', 'bg-blue-50');
                } else {
                    container.classList.remove('border-blue-500', 'bg-blue-50');
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