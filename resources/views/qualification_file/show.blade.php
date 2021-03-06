@extends('layouts.app')

@section('content')
<!-- modal button -->
<div class="container mt-4">
    <div class="row mb-5">
        <div class="col-xs-12 col-sm-12 col-md-6 float-left">
            <h1 class="competitions-title">Qualification file - {{ $qualification_file->name }}</h1>
            <a title="Show qualification file" id="edit" target="_blank" href="{{ $qualification_file->file }}" style="background: none; border: none; padding: 0; outline: inherit;">
                <ion-icon name="reader-outline"></ion-icon>
            </a>
            @if(auth()->user()->education())
            <button id="edit" title="Edit" data-toggle="modal" data-target="#modalEdit" style="background: none; border: none; padding: 0; outline: inherit;">
                <ion-icon name="create-outline"></ion-icon>
            </button>
            @endif
        </div>

        @if(auth()->user()->education())
        <div class="col-xs-12 col-sm-12 col-md-6 text-right comptetitions-button">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">Add work process</button>
        </div>
        @endif
        @if(auth()->user()->student())
        <div class="col-md-6 text-right">
            <h4>Status: {{ auth()->user()->achieved_student_files() .'/'. count(App\Models\Qualification_file::find(1)->competitions) }}</h4>
        </div>
        @endif

    </div>
    <!-- table -->
    <table class="shadow table table-striped table-sm table-hover">
        <thead>
            <tr>
                <th style="padding-left: 20px;" scope="col"><strong>Name</strong> </th>
                @if(auth()->user()->student())
                <th scope="col">File</th>
                <th scope="col">Achieved</th>
                @endif
                @if(!auth()->user()->bpv())
                <th scope="col">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($competitions as $competition)
            <tr>
                <th style="padding-left: 20px;" scope="row">{{ $competition->name }}</th>
                @if(auth()->user()->student())

                <th scope="row"><a target="_blank" href="{{ $student_files->where('competition_id', $competition->id)->pluck('file')->first() }}">{{ basename($student_files->where('competition_id', $competition->id)->pluck('file')->first()) }}</a></th>
                <!-- <th scope="row" style="{{ $student_files->where('competition_id', $competition->id)->pluck('achieved')->first() == 0 ? 'background-color: red;' : 'background-color: green;' }}"></th> -->
                <th scope="row">
                    <ion-icon style="cursor: default; color:{{ $student_files->where('competition_id', $competition->id)->pluck('achieved')->first() == 0 ? 'inherit' : 'green' }};" name="{{ $student_files->where('competition_id', $competition->id)->pluck('achieved')->first() == 0 ? 'square-outline' : 'checkbox-outline' }}"></ion-icon>
                </th>
                @endif
                <td>
                    @if(!auth()->user()->bpv())

                    @if(auth()->user()->education())
                    <button title="Edit" id="edit" data-toggle="modal" data-target="#modalTwo-{{ $competition->id }}" style="background: none; border: none; padding: 0; outline: inherit;">
                        <ion-icon name="create-outline"></ion-icon>
                    </button>
                    <!-- <button type="button" id="edit" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTwo-{{ $competition->id }}">Edit</button> -->
                    <form method="POST" action="{{ url('competition') }}/{{ $competition->id }}"> @csrf
                        @method('DELETE')
                        <!-- <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')" data-toggle="confirmation">Remove</button> -->
                        <button title="Remove" id="remove" type="submit" onclick="return confirm('Are you sure you want to delete this?')" data-toggle="confirmation" style="	background: none; border: none; padding: 0; outline: inherit;">
                            <ion-icon name="close-outline" ></ion-icon>
                        </button>
                    </form>
                    @else
                    <button title="New/edit" id="edit" data-toggle="modal" data-target="#modalAddFile-{{ $competition->id }}" style="background: none; border: none; padding: 0; outline: inherit;">
                        <ion-icon name="add-outline"></ion-icon>
                    </button>
                    <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAddFile-{{ $competition->id }}">Add file</button> -->
                    @if (count($competition->student_files()->where('user_id', auth()->id())->get()) > 0)
                    <form method="POST" action="{{ url('student_file') }}/{{ $student_files->where('competition_id', $competition->id)->pluck('id')->first() }}"> @csrf
                        @method('DELETE')
                        <!-- <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')" data-toggle="confirmation">Remove</button> -->
                        <button title="Remove" id="remove" type="submit" onclick="return confirm('Are you sure you want to delete this?')" data-toggle="confirmation" style="background: none; border: none; padding: 0; outline: inherit;">
                            <ion-icon  name="close-outline" ></ion-icon>
                        </button>
                    </form>
                    @endif

                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- modal create/update student_file -->

@foreach ($competitions as $competition)
<div>
    <div class="modal fade" id="modalAddFile-{{ $competition->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $competition->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-group" method="POST" enctype="multipart/form-data" action="{{ route('student_file.store') }}">
                        @csrf
                        <input type="text" hidden name="user_id" value="{{ Auth()->id() }}">
                        <input type="text" hidden name="competition_id" value="{{ $competition->id }}">
                        <input type="text" hidden name="achieved" value="0">

                        <div class="form-group">
                            <label for="file" class="col-form-label">File: (.pdf only)</label>
                            <input type="file" accept=".pdf" name="file" class="form-control" id="file" required>

                            @error('file')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- modal create competition -->

<div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New work process</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-group" method="POST" enctype="multipart/form-data" action="{{ route('competition.store') }}">
                        <div>
                            @csrf
                            <input type="text" hidden name="qualification_file_id" value="{{ $qualification_file->id }}">

                            <label for="name" class="col-form-label">Name:</label>
                            <input type="text" name="name" class="form-control" id="name" required>

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary float-right mt-3">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal update competition -->
@foreach ($competitions as $competition)
<div>
    <div class="modal fade" id="modalTwo-{{ $competition->id }}" tabindex="-1" role="dialog" aria-labelledby="modalTwoo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit work process</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-group" method="POST" enctype="multipart/form-data" action="{{ route('competition.update', $competition->id) }}">
                        @csrf
                        @method('PATCH')

                        <input type="text" hidden name="qualification_file_id" value="{{ $qualification_file->id }}">
                        <label for="name" class="col-form-label">Name:</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $competition->name }}" required>

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div>
                            <button type="submit" class="btn btn-primary float-right mt-3">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<div>
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit qualification file: {{ $qualification_file->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-group" method="POST" enctype="multipart/form-data" action="{{ route('qualification_file.update', $qualification_file->id ) }}">
                        @csrf
                        @method('PATCH')

                        <input type="text" hidden name="user_id" value="{{ $qualification_file->user_id }}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $qualification_file->name }}" required>

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="file">File (.pdf only)</label>
                            <a target="_blank" href="{{ $qualification_file->file }}">{{ basename($qualification_file->file) }}</a>
                            <input type="file" accept=".pdf" name="file" class="form-control" id="file" value="{{ $qualification_file->file }}">
                        </div>

                        @error('file')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <button type="submit" class="btn btn-primary float-right mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</script>
@if (count($errors) > 0)
<script>
    $(document).ready(function() {
        $('#modalTwo').modal('show');
    });
</script>
@endif
@endsection