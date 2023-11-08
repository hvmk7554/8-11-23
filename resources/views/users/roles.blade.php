@extends('layout.layout')
@section('menunav')
    <button class='btn btn-primary' id='addRoleBtn'> Add role</button>
@endsection
@section('main')
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Role </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input type='text' class='form-control' id='rolename' placeholder='Role name'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id='submitRoleBtn'>Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Role name</th>
                    <th scope="col">Status</th>>
                    <th scope="col">Created at</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
    </div>
    <tbody>
        @foreach ($roles as $key => $item)
            <tr class="">
                <td scope="row">{{ ++$key }}</td>
                <td><span class="editRoleName" data-id="{{ $item->id }}">{{ $item->name }}</span></td>

                <td>
                    <select name="" id="" class="form-control editRoleStatus" data-id="{{ $item->id }}">
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
            addRole();
            editRole();
            switchRole();
            deleteRole();
        });

        function switchRole() {
            $('.editRoleStatus').change(function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');

                var status = $(this).val();
                $.ajax({
                    type: "post",
                    url: "/switchRole",
                    data: {
                        id: id,
                        status: status,
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
            })
        }


        function editRole() {
            $('.editRoleName').click(function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                console.log(id);
                var old = $(this).text();
                $("#rolename").val(old);
                $("#roleModal").modal('show');
                $("#submitRoleBtn").click(function(e) {
                    e.preventDefault();
                    var rolename = $("#rolename").val().trim();
                    if (rolename == '') {
                        Toast.fire({
                            icon: "error",
                            title: "Thiếu tên loại tài khoản"
                        });
                    } else if (rolename == old) {
                        Toast.fire({
                            icon: "error",
                            title: "Tên loại tài khoản chưa thay đổi"
                        });
                    } else {
                        $.ajax({
                            type: "post",
                            url: "/updateRole",
                            data: {
                                id: id,
                                rolename: rolename,
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
                                } else if (res.msg.rolename) {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.msg.rolename
                                    });
                                }
                            }
                        });
                    }
                })
            })
        }




        function addRole() {
            $("#addRoleBtn").click(function(e) {
                e.preventDefault();
                // $("#roleModal").modal('show');
                $("#roleModal").modal('show');
                $("#submitRoleBtn").click(function(e) {
                    e.preventDefault();
                    var rolename = $("#rolename").val().trim();
                    if (rolename == '') {
                        Toast.fire({
                            icon: "error",
                            title: "Thiếu tên loại tài khoản"
                        });
                    } else {
                        $.ajax({
                            type: "post",
                            url: "/roles",
                            data: {
                                rolename: rolename
                            },
                            dataType: "JSON",
                            success: function(res) {
                                if (res.check ==true) {
                                    Toast.fire({
                                            icon: "success",
                                            title: "success"
                                        }).then(
                                            () => {
                                                window.location.reload();
                                            }
                                        )
                                }
                                if (res.msg.rolename) {
                                    Toast.fire({
                                            icon: "error",
                                            title: res.msg.rolename
                                        });
                                }
                                console.log(res);
                            }
                        });
                    }
                });
            });
        }


        function deleteRole() {
            $('.deleteRoleBtn').click(function(e) {
                        e.preventDefault();
                        var id = $(this).attr('data-id');
                        Swal.fire({
                            icon:'question',
                            title: "Delete role?",
                            showDenyButton: false,
                            showCancelButton: true,
                            confirmButtonText: "Yes",                           
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $.ajax({
                    type: "post",
                    url: "/deleteRole",
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
