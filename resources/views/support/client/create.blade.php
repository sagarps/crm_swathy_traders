@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Create New Support Ticket
    <small>Raise a request or bug report</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('support.dashboard') }}">Support</a></li>
    <li class="active">Create Ticket</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-9 col-md-offset-1">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Ticket Information</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('support.tickets.index') }}" class="btn btn-default btn-xs btn-flat">
              <i class="fa fa-arrow-left"></i> Back to Tickets
            </a>
          </div>
        </div>

        <form action="{{ route('support.tickets.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="box-body">
            <!-- Category and Priority Row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                  <label for="category_id">Ticket Category <span class="text-danger">*</span></label>
                  <select name="category_id" id="category_id" class="form-control select2" required style="width: 100%;">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                        @if($cat->description) - ({{ $cat->description }})@endif
                      </option>
                    @endforeach
                  </select>
                  @if($errors->has('category_id'))
                    <span class="help-block">{{ $errors->first('category_id') }}</span>
                  @endif
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group {{ $errors->has('priority') ? 'has-error' : '' }}">
                  <label for="priority">Priority <span class="text-danger">*</span></label>
                  <select name="priority" id="priority" class="form-control" required>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Minor suggestion / Non-blocking)</option>
                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium (Standard correction / Inconvenience)</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Feature interrupted / Time sensitive)</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent (Critical failure / Work halted)</option>
                  </select>
                  @if($errors->has('priority'))
                    <span class="help-block">{{ $errors->first('priority') }}</span>
                  @endif
                </div>
              </div>
            </div>

            <!-- Optional Customer Linkage (if applicable) -->
            @if(isset($customers) && $customers->count() > 0)
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="customer_id">Related Customer (Optional)</label>
                    <select name="customer_id" id="customer_id" class="form-control select2" style="width: 100%;">
                      <option value="">-- None / General Application Ticket --</option>
                      @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                          {{ $c->customer_name }} ({{ $c->customer_phone ?: 'No Phone' }})
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            @endif

            <!-- Subject -->
            <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
              <label for="subject">Subject / Summary <span class="text-danger">*</span></label>
              <input type="text" name="subject" id="subject" class="form-control" 
                     placeholder="Brief summary of the issue or change request" 
                     value="{{ old('subject') }}" required maxlength="255">
              @if($errors->has('subject'))
                <span class="help-block">{{ $errors->first('subject') }}</span>
              @endif
            </div>

            <!-- Detailed Description -->
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
              <label for="description">Detailed Description <span class="text-danger">*</span></label>
              <textarea name="description" id="description" class="form-control" rows="7" 
                        placeholder="Please describe the issue or requested customization in detail. If reporting an error, include steps to reproduce, expected result, and actual result." 
                        required>{{ old('description') }}</textarea>
              @if($errors->has('description'))
                <span class="help-block">{{ $errors->first('description') }}</span>
              @endif
            </div>
@if(1 == 2)
            <!-- Multiple Attachments -->
            <div class="form-group {{ $errors->has('attachments.*') ? 'has-error' : '' }}">
              <label for="attachments">Attachments (Screenshots, Logs, Documents)</label>
              <input type="file" name="attachments[]" id="attachments" multiple class="form-control" 
                     accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt">
              <p class="help-block">
                <i class="fa fa-info-circle"></i> You can select multiple files. Allowed: PDF, DOC/DOCX, XLS/XLSX, PNG, JPG, ZIP. Max: 10 MB per file.
              </p>
              @if($errors->has('attachments.*'))
                <span class="help-block">{{ $errors->first('attachments.*') }}</span>
              @endif
            </div>
@endif
            <div class="callout callout-info" style="margin-top: 15px;">
              <h4><i class="fa fa-shield"></i> Company-Level Security Notice</h4>
              <p>This ticket will be registered under your office <strong>{{ Auth::user()->employee && Auth::user()->employee->office ? Auth::user()->employee->office->office_name : 'SAAD' }}</strong>. All authorized members of your office will be able to review this ticket and its progress.</p>
            </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat btn-lg">
              <i class="fa fa-paper-plane"></i> Raise Ticket
            </button>
            <a href="{{ route('support.tickets.index') }}" class="btn btn-default btn-flat btn-lg pull-right">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

