@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Add Employment History - ' . $alumni->name)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Add Employment History</h2>
                        <p class="text-gray-600">{{ $alumni->name }}</p>
                    </div>
                    <a href="{{ route('alumni.employment-history.index', $alumni) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500">
                        <div class="text-red-700">
                            <h4 class="font-medium mb-2">Please correct the following errors:</h4>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('alumni.employment-history.store', $alumni) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   value="{{ old('company_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Position <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="position" 
                                   name="position" 
                                   value="{{ old('position') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Location
                        </label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               placeholder="City, State, Country"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Employment Type -->
                        <div>
                            <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Employment Type <span class="text-red-500">*</span>
                            </label>
                            <select id="employment_type" 
                                    name="employment_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select Type</option>
                                <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                <option value="freelance" {{ old('employment_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date
                            </label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Leave empty if this is your current position</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Salary -->
                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">
                                Salary
                            </label>
                            <input type="number" 
                                   id="salary" 
                                   name="salary" 
                                   value="{{ old('salary') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                                Currency
                            </label>
                            <select id="currency" 
                                    name="currency" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>CAD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Current Position Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_current" 
                               name="is_current" 
                               value="1"
                               {{ old('is_current') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_current" class="ml-2 block text-sm text-gray-900">
                            This is my current position
                        </label>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Job Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe your role and responsibilities...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Achievements -->
                    <div>
                        <label for="achievements" class="block text-sm font-medium text-gray-700 mb-2">
                            Key Achievements
                        </label>
                        <textarea id="achievements" 
                                  name="achievements" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="List your key achievements and accomplishments...">{{ old('achievements') }}</textarea>
                    </div>

                    <!-- Skills -->
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">
                            Skills Used
                        </label>
                        <input type="text" 
                               id="skills_input" 
                               name="skills_input" 
                               value="{{ old('skills_input', old('skills') ? implode(', ', old('skills')) : '') }}"
                               placeholder="Enter skills separated by commas (e.g., PHP, JavaScript, Project Management)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Separate skills with commas</p>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('alumni.employment-history.index', $alumni) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Save Employment History
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle skills input conversion to array
    const skillsInput = document.getElementById('skills_input');
    const form = skillsInput.closest('form');
    
    form.addEventListener('submit', function(e) {
        const skillsValue = skillsInput.value.trim();
        if (skillsValue) {
            // Convert comma-separated skills to array
            const skillsArray = skillsValue.split(',').map(skill => skill.trim()).filter(skill => skill.length > 0);
            
            // Remove the text input
            skillsInput.remove();
            
            // Add hidden inputs for each skill
            skillsArray.forEach((skill, index) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `skills[${index}]`;
                hiddenInput.value = skill;
                form.appendChild(hiddenInput);
            });
        }
    });
    
    // Handle current position checkbox
    const isCurrentCheckbox = document.getElementById('is_current');
    const endDateInput = document.getElementById('end_date');
    
    isCurrentCheckbox.addEventListener('change', function() {
        if (this.checked) {
            endDateInput.value = '';
            endDateInput.disabled = true;
        } else {
            endDateInput.disabled = false;
        }
    });
    
    // Initialize on page load
    if (isCurrentCheckbox.checked) {
        endDateInput.disabled = true;
    }
});
</script>
@endsection