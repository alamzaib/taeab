<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Countries</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.countries.store') }}" style="margin-bottom: 20px;">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Country Name" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="code" class="form-control" placeholder="Code (e.g., UAE)" maxlength="3">
                </div>
                <div class="col-md-2">
                    <input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="0">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Add Country</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countries as $country)
                    <tr>
                        <form method="POST" action="{{ route('admin.settings.countries.update', $country) }}">
                            @csrf
                            @method('PUT')
                            <td><input type="text" name="name" class="form-control" value="{{ $country->name }}" required></td>
                            <td><input type="text" name="code" class="form-control" value="{{ $country->code }}" maxlength="3"></td>
                            <td><input type="number" name="sort_order" class="form-control" value="{{ $country->sort_order }}"></td>
                            <td>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $country->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$country->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                <a href="{{ route('admin.settings.countries.destroy', $country) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this country?')">Delete</a>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No countries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

