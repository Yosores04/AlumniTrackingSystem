// Confirm Dialog Component for Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('confirmDialog', () => ({
        open: false,
        title: 'Confirm Action',
        message: 'Are you sure you want to perform this action?',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel', 
        confirmButtonClass: 'bg-red-500 hover:bg-red-600',
        type: 'danger', // danger, warning, info
        actionCallback: null,
        
        init() {
            this.$watch('open', (value) => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        },
        
        openModal(options) {
            if (options) {
                this.title = options.title || this.title;
                this.message = options.message || this.message;
                this.confirmButtonText = options.confirmButtonText || this.confirmButtonText;
                this.cancelButtonText = options.cancelButtonText || this.cancelButtonText;
                this.type = options.type || this.type;
                this.actionCallback = options.onConfirm || null;
                
                if (this.type === 'warning') {
                    this.confirmButtonClass = 'bg-yellow-500 hover:bg-yellow-600';
                } else if (this.type === 'info') {
                    this.confirmButtonClass = 'bg-blue-500 hover:bg-blue-600';
                } else {
                    this.confirmButtonClass = 'bg-red-500 hover:bg-red-600';
                }
            }
            this.open = true;
        },
        
        confirm() {
            if (typeof this.actionCallback === 'function') {
                this.actionCallback();
            }
            this.close();
        },
        
        close() {
            this.open = false;
        }
    }));
}); 