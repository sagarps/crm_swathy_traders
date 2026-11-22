@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Support Ticket Categories
    <small>Manage categories for ticket classification</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('admin.support.dashboard') }}">Support Admin</a></li>
    <li class="active">Categories</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <!-- Category List Table -->
    <div class="col-md-8">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tags"></i> Existing Categories</h3>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="bg-gray-light">
                <th style="width: 50px;">Sort</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Tickets Count</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($categories as $cat)
                <tr>
                  <td><span class="badge bg-gray">{{ $cat->sort_order }}</span></td>
                  <td><strong>{{ $cat->name }}</strong></td>
                  <td>{{ $cat->description ?: '-' }}</td>
                  <td><span class="label label-primary">{{ $cat->tickets_count }} tickets</span></td>
                  <td>
                    @if($cat->is_active)
                      <span class="label label-success">Active</span>
                    @else
                      <span class="label label-default">Inactive</span>
                    @endif
                  </td>
                  <td>
                    <button type="button" class="btn btn-xs btn-primary btn-flat btn-edit-category" 
                            data-id="{{ $cat->id }}" 
                            data-name="{{ $cat->name }}" 
                            data-description="{{ $cat->description }}" 
                            data-sort="{{ $cat->sort_order }}" 
                            data-active="{{ $cat->is_active ? '1' : '0' }}">
                      <i class="fa fa-pencil"></i> Edit
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted" style="padding: 20px;">
                    No categories found.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create Category Form -->
    <div class="col-md-4">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-plus-circle"></i> Add New Category</h3>
        </div>
        <form action="{{ route('admin.support.categories.store') }}" method="POST">
          @csrf
          <div class="box-body">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
              <label for="name">Category Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Database Error" value="{{ old('name') }}" required maxlength="100">
              @if($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea name="description" id="description" class="form-control" rows="3" placeholder="Brief description of this ticket category...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
              <label for="sort_order">Sort Order</label>
              <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
            </div>

            <div class="checkbox">
              <label>
                <input type="checkbox" name="is_active" value="1" checked> Active (Visible to clients)
              </label>
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-success btn-flat btn-block">
              <i class="fa fa-save"></i> Save Category
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Edit Category Modal -->
<div class="modal fade" id="modal-edit-category">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-edit-category" action="" method="POST">
        @csrf
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Ticket Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="edit_name">Category Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_name" class="form-control" required maxlength="100">
          </div>
          <div class="form-group">
            <label for="edit_description">Description</label>
            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="edit_sort_order">Sort Order</label>
            <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="is_active" id="edit_is_active" value="1"> Active (Visible to clients)
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Update Category</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).on('click', '.btn-edit-category', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var description = $(this).data('description');
    var sort = $(this).data('sort');
    var active = $(this).data('active');

    var updateUrl = "{{ url('admin/support/categories') }}/" + id + "/update";
    $('#form-edit-category').attr('action', updateUrl);
    $('#edit_name').val(name);
    $('#edit_description').val(description);
    $('#edit_sort_order').val(sort);
    $('#edit_is_active').prop('checked', active == '1');

    $('#modal-edit-category').modal('show');
  });
</script>
@endsection

