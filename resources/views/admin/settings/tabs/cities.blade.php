<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Cities</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.cities.store') }}" style="margin-bottom: 20px;">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <select name="country_id" class="form-control" required>
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="City Name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="sort_order" class="form-control" placeholder="Sort Order" value="0">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Add City</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Country</th>
                    <th>City Name</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <form method="POST" action="{{ route('admin.settings.cities.update', $city) }}">
                            @csrf
                            @method('PUT')
                            <td>
                                <select name="country_id" class="form-control" required>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $city->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="name" class="form-control" value="{{ $city->name }}" required></td>
                            <td><input type="number" name="sort_order" class="form-control" value="{{ $city->sort_order }}"></td>
                            <td>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $city->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$city->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                <a href="{{ route('admin.settings.cities.destroy', $city) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete this city?')">Delete</a>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No cities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

