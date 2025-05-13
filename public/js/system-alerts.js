/**
 * System Alerts - Interactive alert functionality for version management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configure update confirmation
    const updateButtons = document.querySelectorAll('.update-version-btn');
    updateButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const version = this.dataset.version;

            Swal.fire({
                title: 'Update Confirmation',
                html: `
                    <div class="text-left">
                        <p>Are you sure you want to update to <strong>version ${version}</strong>?</p>
                        <p class="mt-2">This action will:</p>
                        <ul class="list-disc pl-5 mt-1 text-left">
                            <li>Create a backup of your current system</li>
                            <li>Download and install the new version</li>
                            <li>Run database migrations</li>
                        </ul>
                        <p class="mt-3 text-amber-600 font-medium">Please make sure you have a backup before proceeding.</p>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#10B981', // Green
                cancelButtonColor: '#6B7280', // Gray
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel',
                footer: '<a href="#">Learn more about system updates</a>'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Updating System',
                        html: 'Please do not close this window. The update may take a few minutes...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    form.submit();
                }
            });
        });
    });

    // Configure rollback confirmation
    const rollbackButtons = document.querySelectorAll('.rollback-version-btn');
    rollbackButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const version = this.dataset.version;

            Swal.fire({
                title: 'Rollback Confirmation',
                html: `
                    <div class="text-left">
                        <p>Are you sure you want to roll back to <strong>version ${version}</strong>?</p>
                        <p class="mt-2">This action will:</p>
                        <ul class="list-disc pl-5 mt-1 text-left">
                            <li>Create a backup of your current system</li>
                            <li>Restore files from the previous version</li>
                            <li>Revert database migrations (if possible)</li>
                        </ul>
                        <p class="mt-3 text-amber-600 font-medium">⚠️ This operation cannot be undone. Some functionality may be lost.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EAB308', // Yellow
                cancelButtonColor: '#6B7280', // Gray
                confirmButtonText: 'Yes, Roll Back',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Rolling Back System',
                        html: 'Please do not close this window. The rollback may take a few minutes...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    form.submit();
                }
            });
        });
    });

    // Show success/error alerts
    if (document.querySelector('.alert-success-message')) {
        const message = document.querySelector('.alert-success-message').textContent;
        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success',
            confirmButtonColor: '#10B981',
        });
    }

    if (document.querySelector('.alert-error-message')) {
        const message = document.querySelector('.alert-error-message').textContent;
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#EF4444',
        });
    }

    if (document.querySelector('.alert-info-message')) {
        const message = document.querySelector('.alert-info-message').textContent;
        Swal.fire({
            title: 'Information',
            text: message,
            icon: 'info',
            confirmButtonColor: '#3B82F6',
        });
    }

    if (document.querySelector('.alert-warning-message')) {
        const message = document.querySelector('.alert-warning-message').textContent;
        Swal.fire({
            title: 'Warning',
            text: message,
            icon: 'warning',
            confirmButtonColor: '#F59E0B',
        });
    }
}); 