@extends('layouts.app')
@push('styles')
@endpush

@section('content')
<!-- Content -->
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="pull-right">
                <a class="btn btn-outline-secondary" href="{{ route('roles.index', withLang()) }}"><i class='bx bxs-chevrons-left' ></i>&nbsp;  Back</a>
            </div>
        </div>
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Role</h5>
                </div>
                <div class="card-body">
                    {!! Form::open(array('route' => ['roles.store', withLang()] ,'method'=>'POST')) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-fullname">Name</label>
                                    <input id="name" type="text" name="name"  class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter your role" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-fullname">Permission</label>
                                    <br/>
                                    @foreach($permissions as $category => $actions)
                                        <div class="card mb-3 permission-group-container">
                                          <div class="card-header bg-light d-flex align-items-center">
                                            <div class="form-check m-0">
                                              <input type="checkbox" class="form-check-input parent-checkbox" id="parent-{{ $category }}">
                                              <label class="form-check-label h5 mb-0 text-capitalize ms-2" for="parent-{{ $category }}">
                                                {{ str_replace('-', ' ', $category) }}
                                              </label>
                                            </div>
                                          </div>
                                          <div class="card-body">
                                            <div class="row">
                                              @foreach($actions as $action)
                                              <div class="col-md-3 col-ms-6 mb-2">
                                                <div class="form-check">
                                                  <input type="checkbox" class="form-check-input child-checkbox @error('permission') is-invalid @enderror" name="permission[]" id="permission-{{ $action->id }}" value="{{ $action->id }}">
                                                  <label for="permission-{{ $action->id }}" class="form-check-label text-capitalize">
                                                    {{ str_replace([$category . '-', '-'], [' ', ' '], $action->name) }}
                                                  </label>
                                                </div>
                                              </div>
                                              @endforeach
                                            </div>
                                          </div>
                                        </div>
                                    @endforeach
                                    @error('permission')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          const parentCheckboxes = document.querySelectorAll('.parent-checkbox');
          parentCheckboxes.forEach(parent => {
            parent.addEventListener('change', function () {
              const container = this.closest('.permission-group-container');
              const childCheckboxes = container.querySelectorAll('.child-checkbox');
              childCheckboxes.forEach(child => {
                child.checked = this.checked;
              });
            });
          });
          const childCheckboxes = document.querySelectorAll('.child-checkbox');
          childCheckboxes.forEach(child => {
            child.addEventListener('change', function () {
              const container = this.closest('.permission-group-container');
              const parent = container.querySelector('.parent-checkbox');
              const allChildren = container.querySelectorAll('.child-checkbox');
              const allChecked = Array.from(allChildren).every(c => c.checked);
              parent.checked = allChecked;
            });
          });
        });
        function submitForm(){
            $('.submit-delete').click();
        }
    </script>
@endpush
