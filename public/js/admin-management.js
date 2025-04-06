$(document).ready(function() {
    // Current page for pagination
    let currentPage = 1;
    
    // Load admins on page load
    loadAdmins(currentPage);
    
    // Handle form submission for creating a new admin
    $('#createAdminForm').on('submit', function(e) {
        e.preventDefault();
        createAdmin();
    });
    
    // Handle pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        currentPage = page;
        loadAdmins(page);
    });
    
    // Handle domain update form submission
    $('#updateDomainForm').on('submit', function(e) {
        e.preventDefault();
        updateDomain();
    });
    
    // Handle status update
    $(document).on('click', '.status-toggle', function() {
        const adminId = $(this).data('id');
        const newStatus = $(this).data('status') === 'active' ? 'inactive' : 'active';
        updateStatus(adminId, newStatus);
    });
    
    // Handle admin deletion
    $(document).on('click', '.delete-admin', function() {
        const adminId = $(this).data('id');
        const adminName = $(this).data('name');
        confirmDelete(adminId, adminName);
    });
    
    // Handle search form
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        searchAdmins();
    });
    
    // Handle edit modal opening
    $(document).on('click', '.edit-domain', function() {
        const adminId = $(this).data('id');
        const domain = $(this).data('domain');
        const adminName = $(this).data('name');
        
        $('#adminIdInput').val(adminId);
        $('#domainInput').val(domain);
        $('#editModalLabel').text('Update Domain for ' + adminName);
        $('#editDomainModal').modal('show');
    });
    
    // ========== FUNCTION DEFINITIONS ==========
    
    // Load admins with pagination
    function loadAdmins(page) {
        $.ajax({
            url: '/api/department-admins?page=' + page,
            type: 'GET',
            beforeSend: function() {
                $('#adminsTable tbody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
            },
            success: function(response) {
                populateTable(response);
                renderPagination(response);
            },
            error: function(xhr) {
                handleError(xhr);
            }
        });
    }
    
    // Populate table with admin data
    function populateTable(data) {
        const admins = data.data;
        let rows = '';
        
        if (admins.length === 0) {
            rows = '<tr><td colspan="7" class="text-center">No administrators found</td></tr>';
        } else {
            admins.forEach(admin => {
                const statusBadge = admin.status === 'active' 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-danger">Inactive</span>';
                
                const statusToggle = admin.status === 'active'
                    ? '<button class="btn btn-sm btn-warning status-toggle" data-id="' + admin.id + '" data-status="active">Deactivate</button>'
                    : '<button class="btn btn-sm btn-success status-toggle" data-id="' + admin.id + '" data-status="inactive">Activate</button>';
                
                rows += `
                    <tr>
                        <td>${admin.name}</td>
                        <td>${admin.email}</td>
                        <td>${admin.department ? admin.department.name : 'N/A'}</td>
                        <td>${admin.domain || 'N/A'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-domain" 
                                data-id="${admin.id}" 
                                data-domain="${admin.domain || ''}" 
                                data-name="${admin.name}">
                                Edit Domain
                            </button>
                            ${statusToggle}
                            <button class="btn btn-sm btn-danger delete-admin" 
                                data-id="${admin.id}" 
                                data-name="${admin.name}">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#adminsTable tbody').html(rows);
    }
    
    // Render pagination links
    function renderPagination(response) {
        let pagination = '<ul class="pagination">';
        
        // Previous page link
        if (response.current_page > 1) {
            pagination += `
                <li class="page-item">
                    <a class="page-link" href="?page=${response.current_page - 1}">Previous</a>
                </li>
            `;
        } else {
            pagination += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }
        
        // Page number links
        for (let i = 1; i <= response.last_page; i++) {
            if (i === response.current_page) {
                pagination += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                pagination += `<li class="page-item"><a class="page-link" href="?page=${i}">${i}</a></li>`;
            }
        }
        
        // Next page link
        if (response.current_page < response.last_page) {
            pagination += `
                <li class="page-item">
                    <a class="page-link" href="?page=${response.current_page + 1}">Next</a>
                </li>
            `;
        } else {
            pagination += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        
        pagination += '</ul>';
        
        $('.pagination-container').html(pagination);
    }
    
    // Create new admin with improved error handling
    function createAdmin() {
        // Reset previous error states
        $('#createAdminForm .is-invalid').removeClass('is-invalid');
        $('#createAdminForm .invalid-feedback').remove();
        
        const formData = $('#createAdminForm').serialize();
        
        $.ajax({
            url: '/api/department-admins',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#createAdminBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            },
            success: function(response) {
                $('#createAdminBtn').prop('disabled', false).text('Save');
                $('#createAdminModal').modal('hide');
                $('#createAdminForm')[0].reset();
                
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Administrator created successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                
                loadAdmins(currentPage);
            },
            error: function(xhr) {
                $('#createAdminBtn').prop('disabled', false).text('Save');
                handleError(xhr);
            }
        });
    }
    
    // Update domain with improved error handling
    function updateDomain() {
        // Reset previous error states
        $('#updateDomainForm .is-invalid').removeClass('is-invalid');
        $('#updateDomainForm .invalid-feedback').remove();
        
        const adminId = $('#adminIdInput').val();
        const domain = $('#domainInput').val();
        
        $.ajax({
            url: `/api/department-admins/${adminId}/domain`,
            type: 'PATCH',
            data: { domain: domain },
            beforeSend: function() {
                $('#updateDomainBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            },
            success: function(response) {
                $('#updateDomainBtn').prop('disabled', false).text('Save Changes');
                $('#editDomainModal').modal('hide');
                
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Domain updated successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                
                loadAdmins(currentPage);
            },
            error: function(xhr) {
                $('#updateDomainBtn').prop('disabled', false).text('Save Changes');
                handleError(xhr);
            }
        });
    }
    
    // Update admin status
    function updateStatus(adminId, newStatus) {
        $.ajax({
            url: `/api/department-admins/${adminId}/status`,
            type: 'PATCH',
            data: { status: newStatus },
            success: function(response) {
                Swal.fire({
                    title: 'Success!',
                    text: `Status updated to ${newStatus}`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                
                loadAdmins(currentPage);
            },
            error: function(xhr) {
                handleError(xhr);
            }
        });
    }
    
    // Delete admin confirmation
    function confirmDelete(adminId, adminName) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${adminName}. This action can be reverted later.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAdmin(adminId);
            }
        });
    }
    
    // Delete admin
    function deleteAdmin(adminId) {
        $.ajax({
            url: `/api/department-admins/${adminId}`,
            type: 'DELETE',
            success: function(response) {
                Swal.fire(
                    'Deleted!',
                    'Administrator has been deleted.',
                    'success'
                );
                
                loadAdmins(currentPage);
            },
            error: function(xhr) {
                handleError(xhr);
            }
        });
    }
    
    // Search/filter admins
    function searchAdmins() {
        const searchParams = $('#searchForm').serialize();
        
        $.ajax({
            url: '/api/department-admins/search?' + searchParams,
            type: 'GET',
            beforeSend: function() {
                $('#adminsTable tbody').html('<tr><td colspan="7" class="text-center">Searching...</td></tr>');
            },
            success: function(response) {
                populateTable(response);
                renderPagination(response);
            },
            error: function(xhr) {
                handleError(xhr);
            }
        });
    }
    
    // Handle API errors
    function handleError(xhr) {
        let errorMessage = 'An error occurred. Please try again.';
        let errorDetails = [];
        
        if (xhr.responseJSON) {
            // Get main error message
            if (xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            // Process detailed error messages
            if (xhr.responseJSON.errors) {
                Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                    // Highlight field with error in the form
                    highlightFieldError(field, messages[0]);
                    
                    // Add to error details for the alert
                    if (Array.isArray(messages)) {
                        messages.forEach(msg => {
                            errorDetails.push(`<li>${field.replace('_', ' ')}: ${msg}</li>`);
                        });
                    }
                });
            }
        } else if (xhr.status === 0) {
            errorMessage = 'Cannot connect to the server. Please check your internet connection.';
        } else if (xhr.status === 404) {
            errorMessage = 'The requested resource was not found.';
        } else if (xhr.status === 500) {
            errorMessage = 'An internal server error occurred. Please try again later.';
        } else if (xhr.status === 403) {
            errorMessage = 'You do not have permission to perform this action.';
        }
        
        // Create error alert with details if available
        let alertContent = errorMessage;
        if (errorDetails.length > 0) {
            alertContent += `<div class="mt-3"><ul class="text-left">${errorDetails.join('')}</ul></div>`;
        }
        
        Swal.fire({
            title: 'Error',
            html: alertContent,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
    
    // Highlight field with error and show message
    function highlightFieldError(fieldName, errorMessage) {
        // Handle array fields (like social_links.0.url)
        if (fieldName.includes('.')) {
            const parts = fieldName.split('.');
            if (parts[0] === 'social_links' && !isNaN(parts[1]) && parts[2]) {
                fieldName = `${parts[0]}[${parts[1]}][${parts[2]}]`;
            }
        }
        
        const $field = $(`[name="${fieldName}"]`);
        if ($field.length) {
            $field.addClass('is-invalid');
            
            // Add error message if it doesn't exist
            let $feedback = $field.siblings('.invalid-feedback');
            if (!$feedback.length) {
                $feedback = $('<div class="invalid-feedback"></div>');
                $field.after($feedback);
            }
            
            $feedback.text(errorMessage);
            
            // Remove error highlight when field is changed
            $field.one('input change', function() {
                $(this).removeClass('is-invalid');
                $feedback.remove();
            });
        }
    }
});
