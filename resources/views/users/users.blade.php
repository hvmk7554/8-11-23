
@extends('layout.layout')

@section('menunav')
    <button class='btn btn-primary' id='addUserBtn'> Add user</button>
@endsection

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
            <select name='' id='userRole' class='form-control '>
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
 
{{-- // mail --}}
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">edit user</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type='text' id='editValue' class='form-control'>
           
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submiteditBtn">Save changes</button>
        </div>
      </div>
    </div>
</div>
 

   <div class='row'>
   @if (count($users)>0)
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
            @foreach ($users as $key => $item)
                <tr> 
                    <td> {{ ++$key }}</td>
                    <td><span class="editUserName" 
                        data-id="{{ $item->id }}">{{ $item->name }}</span></td>
    
                     <td><span class="editUserEmail" 
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
                        <button class="btn btn-danger deleteUserBtn" 
                        data-id="{{ $item->id }}">Xoá</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
  </div> 
   @endif 

   
  <script>

const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

  $(document).ready(function() {
            addUser();  
            editUserRole();   
            editUserStatus(); 
            editUserName();
            editUserMail();
            deleteUser();
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

        function editUserRole() {
            $('.editUserRole').change(function(e) {
                e.preventDefault();
                var id =$(this).attr('data-id');
                console.log(id);
                var role =$(this).val();        
                Swal.fire({
                            icon:'question',
                            title: "Change status?",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            denyButtonText: "No",
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) { 
                        $.ajax({
                            type: "post",
                            url: "/updateUSRole",
                            data: {
                                id:id,
                                role:role,
                            },
                            dataType: "JSON",
                            success: function(res) {
                                if (res.check == true) {
                                    Toast.fire({
                                        icon: "success",
                                        title: "Thay đổi role thành công"
                                    }).then(() => {
                                        window.location.reload();
                                    })
                                }

                                if (res.msg.id) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.id
                                    });
                                } else if (res.msg.role) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.role
                                    });
                        }
                    }
                });
            }else if (result.isDenied){
                              
                            }
            })
           })}
                                    
        
           

            function editUserStatus() {
            $('.editUserStatus').change(function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');

                var status = $(this).val();
                Swal.fire({
                            icon:'question',
                            title: "Change status?",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            denyButtonText: "No",
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                                                                
                $.ajax({
                    type: "post",
                    url: "/updateUSstatus",
                    data: {
                        id:id,
                        status:status,
                    },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.check == true) {
                            Toast.fire({
                                icon: "success",
                                title: "Thay đổi status thành công"
                            }).then(() => {
                                window.location.reload();
                            })
                        }
                        if (res.msg.id) {
                            Toast.fire({
                                icon: "error",
                                title: res.msg.id
                            });
                        } else if (res.msg.status) {
                            Toast.fire({
                                icon: "error",
                                title: res.msg.status
                            });
                        }
                    }
                });
            }else if (result.isDenied){
                              
                            }
            })
           })}

           function editUserName() {
            $('.editUserName').click(function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                console.log(id);
                var old = $(this).text();
                $("#username").val(old);
                $("#UserModal").modal('show');
                $("#submitUserBtn").click(function(e) {
                    e.preventDefault();
                    var username = $("#username").val().trim();
                    if (username == '') {
                        Toast.fire({
                            icon: "error",
                            title: "Thiếu tên  tài khoản"
                        });
                    } else if (username == old) {
                        Toast.fire({
                            icon: "error",
                            title: "Tên  tài khoản chưa thay đổi"
                        });
                    } else {
                        $.ajax({
                            type: "post",
                            url: "/updateUSname",
                            data: {
                                id:id,
                                name:name,
                            },
                            dataType: "JSON",
                            success: function(res) {
                                if (res.check == true) {
                                    Toast.fire({
                                        icon: "success",
                                        title: "Thay đổi thành công"
                                    }).then(() => {
                                        window.location.reload();
                                    })
                                }

                                if (res.msg.id) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.id
                                    });
                                } else if (res.msg.name) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.name
                                    });
                                }
                            }
                        });
                    }
                })
            })
        }

        function editUserMail() {
            $('.editUserMail').click(function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                console.log(id);
                var old = $(this).text();
                $("#editValue").val(old);
                $("#editUserModal").modal('show');
                $("#submiteditBtn").click(function(e) {
                    e.preventDefault();
                    varemail = $("#editValue").val().trim();
                    if (email == '') {
                        Toast.fire({
                            icon: "error",
                            title: "Thiếu email"
                        });     
                    } else if (email == old) {
                        Toast.fire({
                            icon: "error",
                            title: "Tên email chưa thay đổi"
                        });            
                    } else {
                        $.ajax({
                            type: "post",
                            url: "/updateUSmail",
                            data: {
                                id:id,
                                email:email
                            },
                            dataType: "JSON",
                            success: function(res) {
                                if (res.check == true) {
                                    Toast.fire({
                                        icon: "success",
                                        title: "Thay đổi thành công"
                                    }).then(() => {
                                        window.location.reload();
                                    })
                                }

                                if (res.msg.id) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.id
                                    });
                                } else if (res.msg.email) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.email
                                    });
                                }
                            }
                        });
                    }
                })
            })
        }


  function deleteUser() {
            $('.deleteUserBtn').click(function(e) {
                        e.preventDefault();
                        var id = $(this).attr('data-id');
                        Swal.fire({
                            icon:'question',
                            title: "Delete user",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Yes",                           
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $.ajax({
                    type: "post",
                    url: "/deleteUser",
                    data: {
                        id: id,
                    },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.check == true) {
                            Toast.fire({
                                icon: "success",
                                title: "delete thành công"
                            }).then(() => {
                                window.location.reload();
                            })
                        }
                        if (res.msg.id) {
                            Toast.fire({
                                icon: "error",
                                title: res.msg.id
                            });
                        } else if (res.msg.status) {
                            Toast.fire({
                                icon: "error",
                                title: res.msg.status
                            });
                        }
                    }
                });
                            } else if (result.isDenied) {
                                
                            }
                        });
                    })}

  </script>
@endsection
