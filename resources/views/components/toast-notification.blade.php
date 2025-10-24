<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3 pointer-events-none">
    <!-- Toasts will be inserted here dynamically -->
</div>

<!-- Toast Notification Styles and Scripts -->
<style>
    .toast {
        pointer-events: auto;
        animation: slideInRight 0.3s ease-out;
        max-width: 400px;
    }
    
    .toast.hiding {
        animation: slideOutRight 0.3s ease-in;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .toast-progress {
        animation: progress 5s linear;
    }
    
    @keyframes progress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
</style>

<script>
    function showToast(message, type = 'success', duration = 5000) {
        const container = document.getElementById('toast-container');
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast bg-white rounded-xl shadow-2xl border overflow-hidden ${
            type === 'success' ? 'border-green-200' :
            type === 'error' ? 'border-red-200' :
            type === 'warning' ? 'border-yellow-200' :
            type === 'info' ? 'border-buksu-navy-200' : 'border-gray-200'
        }`;
        
        // Icon SVGs
        const icons = {
            success: `<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            error: `<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            warning: `<svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>`,
            info: `<svg class="w-6 h-6 text-buksu-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`
        };
        
        toast.innerHTML = `
            <div class="flex items-start p-4 gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    ${icons[type] || icons.success}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 break-words">${message}</p>
                </div>
                <button onclick="this.closest('.toast').remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-yellow-500' :
                type === 'info' ? 'bg-buksu-navy-500' : 'bg-gray-500'
            } toast-progress"></div>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // Check for session messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif
        
        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
        
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif
    });
</script>
