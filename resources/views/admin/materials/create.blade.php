@extends('layouts.app')

@section('no_sidebar', true)

@section('title', 'Add New Material - Oweru')

@section('content')
<style>
    /* Oweru Brand Colors */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
        --oweru-success: #27ae60;
        --oweru-danger: #e74c3c;
        --oweru-warning: #f39c12;
        --oweru-gradient: linear-gradient(135deg, #09172A 0%, #2D3A58 100%);
        --oweru-gradient-gold: linear-gradient(135deg, #C89128 0%, #E5B972 100%);
    }

    /* Force full-width layout - hide sidebar completely */
    .sidebar,
    [class*="sidebar"],
    .col-md-3,
    .col-lg-2,
    .navbar-vertical {
        display: none !important;
    }

    /* Adjust main content to take full width */
    .main-content,
    .col-md-9,
    .col-lg-10,
    .content-wrapper {
        width: 100% !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
        max-width: 100% !important;
    }

    .container-fluid {
        padding-left: 20px !important;
        padding-right: 20px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .add-material-page {
        background: var(--oweru-light);
        min-height: 100vh;
        padding: 2rem 0;
        width: 100% !important;
        margin: 0 !important;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(9, 23, 42, 0.1);
        border: none;
        overflow: hidden;
        width: 100%;
    }

    .card-header-oweru {
        background: var(--oweru-gradient);
        border: none;
        padding: 1.5rem 2rem;
    }

    .guideline-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.1);
        border: none;
        height: 100%;
    }

    .guideline-header {
        background: var(--oweru-gradient-gold);
        color: var(--oweru-dark);
        border: none;
        padding: 1rem 1.5rem;
    }

    .btn-oweru-primary {
        background: var(--oweru-gradient-gold);
        border: none;
        border-radius: 10px;
        color: var(--oweru-dark);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(200, 145, 40, 0.3);
        color: var(--oweru-dark);
    }

    .btn-oweru-secondary {
        border: 2px solid var(--oweru-gray);
        border-radius: 10px;
        color: var(--oweru-gray);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-secondary:hover {
        background: var(--oweru-gray);
        color: white;
        transform: translateY(-2px);
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--oweru-gold);
        box-shadow: 0 0 0 0.2rem rgba(200, 145, 40, 0.25);
        transform: translateY(-2px);
    }

    .form-control.is-invalid {
        border-color: var(--oweru-danger);
    }

    .form-control.is-valid {
        border-color: var(--oweru-success);
    }

    .form-label {
        font-weight: 600;
        color: var(--oweru-dark);
        margin-bottom: 0.5rem;
    }

    .required-star {
        color: var(--oweru-danger);
    }

    .image-preview {
        width: 100%;
        height: 200px;
        border: 2px dashed var(--oweru-gold);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--oweru-light);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .image-preview:hover {
        border-color: var(--oweru-secondary);
        background: rgba(200, 145, 40, 0.05);
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .image-preview-placeholder {
        text-align: center;
        color: var(--oweru-gray);
    }

    .image-preview-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--oweru-gradient-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--oweru-dark);
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .page-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.1);
        border-left: 5px solid var(--oweru-gold);
        width: 100%;
    }

    .futura-font {
        font-family: 'Futura PT', Arial, sans-serif;
    }

    .poppins-font {
        font-family: 'Poppins', Arial, sans-serif;
    }

    /* Ensure full width for all containers */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
    }

    .col-12 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .main-card {
            margin-bottom: 1rem;
        }
        
        .form-control {
            padding: 0.6rem 0.8rem;
        }
    }
</style>

<div class="add-material-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2 futura-font text-oweru-dark">
                        <i class="fas fa-plus-circle me-2 text-oweru-gold"></i>
                        Add New Material
                    </h1>
                    <p class="mb-0 poppins-font text-oweru-gray">Add a new construction material to the Oweru platform</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('admin.materials') }}" class="btn-oweru-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Materials
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading futura-font text-danger">Please fix the following errors:</h6>
                        <ul class="mb-0 poppins-font">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8 mb-4">
                <div class="main-card">
                    <div class="card-header-oweru text-white">
                        <h6 class="m-0 font-weight-bold futura-font">
                            <i class="fas fa-bricks me-2"></i>
                            Material Details
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" id="materialForm">
                            @csrf

                            <!-- Basic Information -->
                            <div class="mb-4">
                                <h5 class="futura-font text-oweru-dark mb-3">
                                    <i class="fas fa-info-circle me-2 text-oweru-gold"></i>
                                    Basic Information
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label futura-font">
                                            Material Name <span class="required-star">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="Enter material name" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="sw_name" class="form-label futura-font">
                                            Swahili Name
                                        </label>
                                        <input type="text" class="form-control @error('sw_name') is-invalid @enderror" 
                                               id="sw_name" name="sw_name" value="{{ old('sw_name') }}"
                                               placeholder="Enter Swahili name (optional)">
                                        @error('sw_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="mb-4">
                                <h5 class="futura-font text-oweru-dark mb-3">
                                    <i class="fas fa-align-left me-2 text-oweru-gold"></i>
                                    Descriptions
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label futura-font">
                                        Description <span class="required-star">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4"
                                              placeholder="Describe the material, its uses, and benefits..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sw_description" class="form-label futura-font">
                                        Swahili Description
                                    </label>
                                    <textarea class="form-control @error('sw_description') is-invalid @enderror" 
                                              id="sw_description" name="sw_description" rows="4"
                                              placeholder="Enter Swahili description (optional)">{{ old('sw_description') }}</textarea>
                                    @error('sw_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing & Image -->
                            <div class="mb-4">
                                <h5 class="futura-font text-oweru-dark mb-3">
                                    <i class="fas fa-tags me-2 text-oweru-gold"></i>
                                    Pricing & Media
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label futura-font">
                                            Price (TZS) <span class="required-star">*</span>
                                        </label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" 
                                               min="1000" step="0.01" placeholder="1000.00" required>
                                        @error('price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text poppins-font text-oweru-gray mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Minimum price is TZS 1,000
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="image" class="form-label futura-font">
                                            Material Image
                                        </label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                               id="image" name="image" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text poppins-font text-oweru-gray mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            JPEG, PNG, JPG, GIF • Max 2MB
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Preview -->
                            <div class="mb-4">
                                <label class="form-label futura-font">Image Preview</label>
                                <div class="image-preview" id="imagePreview">
                                    <div class="image-preview-placeholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p class="mb-0 poppins-font">Upload an image to see preview</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn-oweru-primary flex-fill">
                                    <i class="fas fa-save me-2"></i>Create Material
                                </button>
                                <a href="{{ route('admin.materials') }}" class="btn-oweru-secondary flex-fill">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="col-lg-4 mb-4">
                <div class="guideline-card">
                    <div class="guideline-header futura-font">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            Material Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Best Practices -->
                        <div class="mb-4">
                            <div class="feature-icon mx-auto">
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="futura-font text-oweru-dark mb-3">Best Practices</h6>
                            <ul class="poppins-font small text-oweru-gray">
                                <li class="mb-2">Use clear, descriptive names that users can easily understand</li>
                                <li class="mb-2">Provide detailed descriptions including material specifications</li>
                                <li class="mb-2">Set competitive market prices for better adoption</li>
                                <li class="mb-2">Upload high-quality images showing the material clearly</li>
                                <li>Include both English and Swahili names when possible</li>
                            </ul>
                        </div>

                        <!-- Purchase Options -->
                        <div class="mb-4">
                            <div class="feature-icon mx-auto" style="background: var(--oweru-gradient); color: white;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h6 class="futura-font text-oweru-dark mb-3">Purchase Options</h6>
                            <div class="poppins-font small text-oweru-gray">
                                <p class="mb-2">Users will have two purchase methods:</p>
                                <div class="mb-2">
                                    <strong class="text-oweru-success">Direct Purchase</strong>
                                    <p class="mb-1">Full payment upfront with immediate delivery</p>
                                </div>
                                <div>
                                    <strong class="text-oweru-gold">Lipa Kidogo</strong>
                                    <p class="mb-0">Flexible installment payments over time</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tips -->
                        <div class="alert alert-warning border-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="alert-heading futura-font mb-1">Pro Tip</h6>
                                    <p class="mb-0 poppins-font small">Materials are automatically set to active status and will be immediately available for purchase.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Force full width layout
    document.body.style.overflowX = 'hidden';
    const mainContainer = document.querySelector('.container-fluid');
    if (mainContainer) {
        mainContainer.style.maxWidth = '100%';
        mainContainer.style.marginLeft = '0';
        mainContainer.style.marginRight = '0';
        mainContainer.style.paddingLeft = '20px';
        mainContainer.style.paddingRight = '20px';
    }

    // Remove any sidebar elements that might still be in DOM
    const sidebarElements = document.querySelectorAll('.sidebar, .col-md-3, .col-lg-2, [class*="sidebar"]');
    sidebarElements.forEach(element => {
        element.style.display = 'none';
    });

    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.addEventListener('load', function() {
                imagePreview.innerHTML = `<img src="${this.result}" alt="Preview" class="img-fluid">`;
                imagePreview.style.borderStyle = 'solid';
                imagePreview.style.borderColor = 'var(--oweru-success)';
            });
            
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = `
                <div class="image-preview-placeholder">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p class="mb-0 poppins-font">Upload an image to see preview</p>
                </div>
            `;
            imagePreview.style.borderStyle = 'dashed';
            imagePreview.style.borderColor = 'var(--oweru-gold)';
        }
    });

    // Form validation enhancement
    const form = document.getElementById('materialForm');
    const inputs = form.querySelectorAll('input, textarea');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Price formatting
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', function() {
        let value = parseFloat(this.value);
        if (value < 1000) {
            this.style.borderColor = 'var(--oweru-danger)';
        } else {
            this.style.borderColor = '';
        }
    });

    // Auto-focus first input
    document.getElementById('name').focus();
});
</script>
@endpush
@endsection