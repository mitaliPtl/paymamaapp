var allRoles = $('#allRoles').val();
var allRoleAlias = $('#roleAlias').val();
allRoles = allRoles ? JSON.parse(allRoles) : [];
allRoleAlias = allRoleAlias ? JSON.parse(allRoleAlias) : [];

// Handle By Angular
var app = angular.module('myApp', []);
app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%=');
    $interpolateProvider.endSymbol('%>');
});

app.controller('addEditUserCtrl', function ($scope, $http) {
    // default post header
    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    $http.defaults.headers.post['dataType'] = 'json';
    $scope.parentRoles = [];

    $scope.init = () => {
        setTimeout(() => {
            $scope.parentUser = "";
            $scope.parentFosUser = "";
            $scope.cityId = "";
            checkEdit();
        }, 500);
    }


    // Set Parent Role On Basis of Role Selelection
    $('#roleId').change(function () {
        $('#parent_user_id').val('');
        var parentRoles = [];
        var alias = $('option:selected', this).attr('data-alias');
        allRoles.forEach(obj => {
            if (alias == obj['alias'] && alias == allRoleAlias['RETAILER']) {
                parentRoles = allRoles.filter(function (childObj) {
                    if (childObj['alias'] == allRoleAlias['SYSTEM_ADMIN'] || childObj['alias'] == allRoleAlias['DISTRIBUTOR'])
                        return childObj;
                });
            } else if (alias == obj['alias'] && alias == allRoleAlias['DISTRIBUTOR']) {
                parentRoles = allRoles.filter(function (childObj) {
                    if (childObj['alias'] == allRoleAlias['SYSTEM_ADMIN'])
                        return childObj;
                });
            } else if (alias == obj['alias'] && alias == allRoleAlias['FOS']) {
                parentRoles = allRoles.filter(function (childObj) {
                    if (childObj['alias'] == allRoleAlias['DISTRIBUTOR'])
                        return childObj;
                });
            }
        });

        $scope.$apply(() => {
            $scope.parentRoles = parentRoles;
        });
    });

    $('#parent_role_id').change(() => {
        let parentRoleId = $('#parent_role_id').val();
        getUserTypeByParentRole(parentRoleId);
    });

    var getUserTypeByParentRole = (parentRoleId) => {
        let apiUrl = "/get_user_frm_parent_role_id";
        $http({
            method: "POST",
            url: apiUrl,
            data: $.param({ parent_role_id: parentRoleId }),
        }).then(function success(response) {
            $scope.userListByParent = response['data'];
            setTimeout(() => {
                if ($('#hidden_parent_user_id').val()) {
                    $('#parent_user_id').val($('#hidden_parent_user_id').val());

                    // if($('#notAdminUserId').val())
                    // $('#parent_user_id').prop('disabled',true);
                }
            }, 0);
        }, function error(response) {
            // toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
        });
    }

    $('#parent_user_id').change(() => {
        let parentUserId = $('#parent_user_id').val();
        getFosTypeByParentUser(parentUserId);
    });
    var getFosTypeByParentUser = (parentUserId) => {
        console.log(parentUserId);
        let apiUrl = "/get_fos_frm_dis";
        $http({
            method: "POST",
            url: apiUrl,
            data: $.param({ parent_user_id: parentUserId }),
        }).then(function success(response) {
            $scope.fosListByParent = response['data'];
            console.log(response['data']);
            setTimeout(() => {
                if ($('#hidden_fos_id').val()) {
                    $('#fos_id').val($('#hidden_fos_id').val());

                    // if($('#notAdminUserId').val())
                    // $('#parent_user_id').prop('disabled',true);
                }
            }, 0);
        }, function error(response) {
            // toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
        });
    }


    $('#state_id').change(() => {
        let stateId = $('#state_id').val();
        getCityByState(stateId);
    });

    var getCityByState = (stateId) => {
        let apiUrl = "/get_city_frm_state_id";
        $http({
            method: "POST",
            url: apiUrl,
            data: $.param({ state_id: stateId }),
        }).then(function success(response) {
            $scope.cityListByState = response['data'];
            setTimeout(() => {
                if ($('#hidden_district_id').val()) {
                    $('#district_id').val($('#hidden_district_id').val());
                }
            }, 0);
        }, function error(response) {
            // toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
        });
    }

    var checkEdit = () => {
        if ($('#user_id').val() || $('#notAdminUserId').val()) {
            if($('#roleId').val()){
                $('#roleId').trigger('change');
                let parentUserType = $('#hidden_parent_user_type').val();
                $('#parent_role_id').val(parentUserType);
                $('#parent_role_id').trigger('change');

                // if($('#notAdminUserId').val())
                // $('#parent_role_id').prop('disabled',true);
            }
            let parentRoleId = $('#parent_role_id').val();
            getUserTypeByParentRole(parentRoleId);
            let stateId = $('#state_id').val();
            getCityByState(stateId);
            let parentUserId = $('#hidden_parent_user_id').val();
            getFosTypeByParentUser(parentUserId);

        }
    }

    $scope.init();
});