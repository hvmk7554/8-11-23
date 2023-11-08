@extends('layout.layout')
@section('main')
 <div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="UserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="UserModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type='text' class='form-control mb-2' id='username' placeholder='User name'>
            <input type='text' class='form-control mb-2' id='email' placeholder='Email'>
             <label for=''>Loai tk </label>
            <select name='' id='' class='form-control '>
                @foreach ($roles as  $item)
                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                @endforeach
            </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitUserBtn">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-primary">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">User  name</th>
                <th scope="col">Email</th>
                <th scope="col">Role </th>
                <th scope="col">Status</th>
                <th scope="col">Created at</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $key => $item)
                <tr> 
                    <td> {{ ++$key }}</td>
                    <td><span class="pointer editUserName" 
                        data-id="{{ $item->id }}">{{ $item->name }}</span></td>
    
                     <td><span class="pointer editUserEmail" 
                            data-id="{{ $item->id }}">{{ $item->name }}</span></td>

                    <td>
                         <select name="" id="" class="form-control editUserRole" 
                         data-id="{{ $item->id }}">
                         @foreach ($roles as $item1)
                                    @if ($item1->id == $item->idRole)
                                        <option value="{{ $item1->id }}" selected>{{ $item1->name }} </option>                       
                                    @else
                                        <option value="{{ $item1->id }}">{{ $item1->name }}</option>
                                    @endif
                        @endforeach
                        </select>
                    </td>      
                    <td>
                        <select name="" id="" class="form-control editUserStatus" 
                        data-id="{{ $item->id }}">
                            @if ($item->status == 0)
                                <option value="0" selected> Locking</option>
                                <option value="1">Opening</option>
                            @else
                                <option value="0">Locking</option>
                                <option value="1" selected>Opening</option>
                            @endif
                        </select>
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <button class="btn btn-danger deleteRoleBtn" 
                        data-id="{{ $item->id }}">Xoá</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
</div>
<script>

$(document).ready(function() {
            addUser();        
        });

        function addUser() {
            $("#addUserBtn").click(function(e) {
                e.preventDefault();
                // $("#roleModal").modal('show');
                $("#UserModal").modal('show');
                $("#submitUserBtn").click(function(e) {
                    e.preventDefault();
                    var name = $("#username").val().trim();
                    var email = $("#email").val().trim();
                    var idRole = $("#userRole option:selected").val();
                    if (name == '') {
                        Toast.fire({
                            icon: "error",
                            title: "Thiếu tên loại tài khoản"
                        });
                    } else {
                        $.ajax({
                            type: "post",
                            url: "/users",
                            data: {
                                name:name,
                                email:email,
                                idRole:idRole
                            },
                            dataType: "JSON",
                            success: function(res) {
                                if (res.check ==true
                                ) {
                                    Toast.fire({
                                            icon: "success",
                                            title: "success"
                                        }).then(
                                            () => {
                                                window.location.reload();
                                            });
                                }
                                if (res.msg.name) {
                                    Toast.fire({
                                            icon: "error",
                                            title: res.msg.name
                                        });
                                }

                                if (res.msg.email) {
                                    Toast.fire({
                                            icon: "error",
                                            title: res.msg.email
                                        });
                                }

                                if (res.msg.idRole) {
                                    Toast.fire({
                                            icon: "error",
                                            title: res.msg.idRole
                                        });
                                }

                                console.log(res);
                            }
                        });
                    }
                });
            });
        }

</script>
@endsection
@section('menunav')
    <button class='btn btn-primary' id='addUserBtn'> Add user </button>
@endsection