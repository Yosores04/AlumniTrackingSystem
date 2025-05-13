@extends('layouts.instructor')

@section('title', 'Import Alumni')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Import Alumni Data</h6>
                        <a href="{{ route('instructor.alumni.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border bg-light">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Upload Instructions</h6>
                                    
                                    <p class="text-sm mb-3">
                                        Follow these steps to import alumni data:
                                    </p>
                                    
                                    <ol class="text-sm mb-4">
                                        <li class="mb-2">Prepare your CSV file with the following columns: 
                                            <ul class="mt-1">
                                                <li><strong>Required:</strong> first_name, last_name, email</li>
                                                <li><strong>Optional:</strong> phone, address, city, state, zip, country, batch_year, graduation_date, department, degree, employment_status, current_employer, job_title, linkedin_url, notes</li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">Make sure your CSV file uses a comma (,) as the delimiter</li>
                                        <li class="mb-2">The first row should contain the column headers</li>
                                        <li class="mb-2">For dates, use the YYYY-MM-DD format</li>
                                        <li>For employment_status, use one of: employed, unemployed, self_employed, student, other</li>
                                    </ol>
                                    
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> Download Sample CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <form action="{{ route('instructor.alumni.import.process') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="import_file" class="form-label">Select CSV File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="import_file" name="import_file" accept=".csv,.txt" required>
                                    <small class="form-text text-muted">Max file size: 10MB</small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="header_row" name="header_row" value="1" checked>
                                    <label class="form-check-label" for="header_row">
                                        First row contains column headers
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="overwrite_existing" name="overwrite_existing" value="1">
                                    <label class="form-check-label" for="overwrite_existing">
                                        Update existing records (based on email match)
                                    </label>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="mark_verified" name="mark_verified" value="1">
                                    <label class="form-check-label" for="mark_verified">
                                        Mark all imported records as verified
                                    </label>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-file-import me-1"></i> Import Alumni Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">Important Note</h6>
                                    <p class="mb-0">The import process may take a few moments depending on the size of your file. Do not refresh the page during the import process.</p>
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