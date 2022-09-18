var addEditModal = $('#slidderBannerAddModal');
var addformID = $('#addSlidderBannerForm');

var addRedirectModal = $('#redirectBannerModal');


// On Modal show check mode and change the action text
addEditModal.on('show.bs.modal', function () {
    $slidder_banner_id = $('#slidder_banner_id').val();
    if ($slidder_banner_id == 0) {
        $('.submit-btn').html('Add');
        $('.modal-action-name').html('Add');
    } else {
        $('.submit-btn').html('Update');
        $('.modal-action-name').html('Update');
    }
});

// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#slidder_banner_id').val(0);
    var addForm = $("#addServiceTypeForm");
    addformID.data('validator').resetForm();
    addformID[0].reset();
});

addformID.submit(function () {
    if (addformID.valid()) {
        $('.submit-btn').prop('disabled', true);
    }
});

// Delete status script starts 
$('.delete-btn').click(function () {

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                var rowId = $(this).data('id');
                $.get('/slidder_banner_delete?id=' + rowId, function (data) {
                    if (data) {
                        swal("Deleted Successfully!", {
                            icon: "success",
                            buttons: false
                        });
                        setTimeout(() => {
                            location.reload(true);
                        }, 500);
                    }
                });
            }
        });
});



// Handle By Angular
var app = angular.module('myApp', []);
app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%=');
    $interpolateProvider.endSymbol('%>');
});

app.controller('slidderBannerCtrl', function ($scope) {
    $scope.bannerFileList = [];
    $scope.bannerFileId = [];

    

    $('#load-add-modal').click(function () {
        $("#slidderBannerAddModal").modal('show');
        $scope.bannerFileId = [];
    });

    // File Upload Script starts
    $('#banner-file-up-btn').click(() => {
        // $('#balanceReqModal').modal('hide');
        $('#choosebannerFile').val('');
        $('.custom-file-label').html('Select File');
        $('#bannerFileUploadMdl').modal('show');
    });

    $('#bannerFileUploadForm').submit(function (e) {

        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',z
            url: "upload-file",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                this.reset();
                $('#choosebannerFile').val('');
                $('.custom-file-label').html('Select File');
                $('#bannerFileUploadMdl').modal('hide');
                if (data) {
                    $scope.$apply(function () {
                        $scope.bannerFileList.push(data['file_path']);
                        $scope.bannerFileId.push(data['id']);
                        $('#image_file_ids').val($scope.bannerFileId);
                    });

                    $('#image_file_ids').valid();
                }

                toastr.success('Image has been uploaded successfully');
            },
            error: function (data) {
                toastr.error("Failed");
            }
        });
    });
    // File Upload Script ends

    // Edit Functionality
    $('.edit-btn').click(function () {
        var slidder_banner_id = $(this).val();
        $.get('/edit_slidder_banner?slidder_banner_id=' + slidder_banner_id, function (data) {

            $('#slidder_banner_id').val(data.id);
            $('#role_id').val(data.role_id);
            $('#platform').val(data.platform);
            $('#location').val(data.location);
            $('#image_file_ids').val(data.image_file_ids);  

            // console.log(data.image_file_ids);
            // return;

            var filePathList = data['file_path_list'] ? data['file_path_list'] : null ;
            if(filePathList){
                $scope.$apply(function () {
                    $scope.bannerFileId = data.image_file_ids;
                    $scope.bannerFileList = filePathList;
                });
            }           

            addEditModal.modal('show');
        })
    });

     // Edit Functionality
     $('.redirect-btn').click(function () {
        var slidder_banner_id = $(this).val();
        console.log(slidder_banner_id);
        $.get('/edit_slidder_banner?slidder_banner_id=' + slidder_banner_id, function (data) {

            $('#redirect_banner_id').val(data.id);
            
            // console.log(link_arr);
            $('#re_image_file_ids').val(data.image_file_ids);  
            $("#redirect_links").empty();
            blank_val='';
            $.each( data.file_path_list, function( index, value ){
                
                if (data.redirect_link) {
                    link_arr = JSON.parse(data.redirect_link);
                
                    link_value =  (link_arr[index]) ? link_arr[index] : blank_val ;
                    $( "#redirect_links" ).append( ' <div class="row">'+
                                                '<div class="col-4">'+
                                                    '<img  class="mb-2" src="'+$('#website_url').val() + value+'" style="width:80px;height:70px;border:1px solid #80808029">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<input type="text" id="redirect_link" name="redirect_link[]"  class="form-control " value="'+ link_value+'" placeholder="Enter Redirect Link" >'+                              

                                                '</div>'+
                                            '</div>' );
                }else{
                   
                    $( "#redirect_links" ).append( ' <div class="row">'+
                                                '<div class="col-4">'+
                                                    '<img  class="mb-2" src="'+$('#website_url').val() + value+'" style="width:80px;height:70px;border:1px solid #80808029">'+
                                                '</div>'+
                                                '<div class="col-8">'+
                                                    '<input type="text" id="redirect_link" name="redirect_link[]"  class="form-control " value="" placeholder="Enter Redirect Link" >'+                              

                                                '</div>'+
                                            '</div>' );
                }

            });

            // console.log(data);
            // return;

            var filePathList = data['file_path_list'] ? data['file_path_list'] : null ;
            

            addRedirectModal.modal('show');
        })
    });

    // Reset Payment modal close
    $('#slidderBannerAddModal').on('hidden.bs.modal', function () {
        $scope.$apply(function () {
            $scope.bannerFileId = [];
            $scope.bannerFileList = [];
        });
    });
    $('#redirect_banner_id').on('hidden.bs.modal', function () {
        $scope.$apply(function () {
            $scope.redi_bannerFileId = [];
            $scope.redi_bannerFileList = [];
        });
    });
});