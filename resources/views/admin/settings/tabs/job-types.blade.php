<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Job Types</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.job-types.store') }}" style="margin-bottom: 20px;">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Job Type Name" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="0">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Add Job Type</button>
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
                @forelse($jobTypes as $jobType)
                    <tr>
                        <form method="POST" action="{{ route('admin.settings.job-types.update', $jobType) }}">
                            @csrf
                            @method('PUT')
                            <td><input type="text" name="name" class="form-control" value="{{ $jobType->name }}" required></td>
                            <td><input type="number" name="sort_order" class="form-control" value="{{ $jobType->sort_order }}"></td>
                            <td>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $jobType->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$jobType->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                <a href="{{ route('admin.settings.job-types.destroy', $jobType) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this job type?')">Delete</a>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No job types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

