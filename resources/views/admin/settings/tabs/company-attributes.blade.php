<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Company Attributes</h3>
    </div>
    <div class="card-body">
        <!-- Company Sizes Section -->
        <div style="margin-bottom: 40px;">
            <h4 style="color: #235181; margin-bottom: 20px;">Company Sizes</h4>
            <form method="POST" action="{{ route('admin.settings.company-sizes.store') }}" style="margin-bottom: 20px;">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Company Size Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="0">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Add Company Size</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companySizes ?? [] as $companySize)
                        <tr>
                            <form method="POST" action="{{ route('admin.settings.company-sizes.update', $companySize) }}">
                                @csrf
                                @method('PUT')
                                <td><input type="text" name="name" class="form-control" value="{{ $companySize->name }}" required></td>
                                <td><input type="number" name="sort_order" class="form-control" value="{{ $companySize->sort_order }}"></td>
                                <td>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $companySize->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$companySize->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    <a href="{{ route('admin.settings.company-sizes.destroy', $companySize) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this company size?')">Delete</a>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No company sizes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Industries Section -->
        <div style="margin-bottom: 40px;">
            <h4 style="color: #235181; margin-bottom: 20px;">Industries</h4>
            <form method="POST" action="{{ route('admin.settings.industries.store') }}" style="margin-bottom: 20px;">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Industry Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="0">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Add Industry</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($industries ?? [] as $industry)
                        <tr>
                            <form method="POST" action="{{ route('admin.settings.industries.update', $industry) }}">
                                @csrf
                                @method('PUT')
                                <td><input type="text" name="name" class="form-control" value="{{ $industry->name }}" required></td>
                                <td><input type="number" name="sort_order" class="form-control" value="{{ $industry->sort_order }}"></td>
                                <td>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $industry->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$industry->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    <a href="{{ route('admin.settings.industries.destroy', $industry) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this industry?')">Delete</a>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No industries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Organization Types Section -->
        <div style="margin-bottom: 40px;">
            <h4 style="color: #235181; margin-bottom: 20px;">Organization Types</h4>
            <form method="POST" action="{{ route('admin.settings.organization-types.store') }}" style="margin-bottom: 20px;">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Organization Type Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="0">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Add Organization Type</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizationTypes ?? [] as $organizationType)
                        <tr>
                            <form method="POST" action="{{ route('admin.settings.organization-types.update', $organizationType) }}">
                                @csrf
                                @method('PUT')
                                <td><input type="text" name="name" class="form-control" value="{{ $organizationType->name }}" required></td>
                                <td><input type="number" name="sort_order" class="form-control" value="{{ $organizationType->sort_order }}"></td>
                                <td>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $organizationType->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$organizationType->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    <a href="{{ route('admin.settings.organization-types.destroy', $organizationType) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this organization type?')">Delete</a>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No organization types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

