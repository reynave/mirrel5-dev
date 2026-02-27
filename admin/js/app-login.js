var app = angular.module('myApp', []);
app.controller('loginController', function ($scope, $http) {
    $scope.loading=false;
    $scope.note;
    $scope.email = "";
    $scope.password = "";
    console.log('CMS link : ',cms);

    $scope.getCookie = function(cname){
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
    }

    if( $scope.getCookie('mirrel5Login') ){
        $scope.note = "Redirect to Content Management System";
        window.location.href = cms;
    }

   
    $scope.submit = function () {
        data = {
            email: $scope.email,
            password: $scope.password,
        }
        $scope.loading=true; 
        $http({
            method: "POST",
            url: base_url +"login/access",
            data: data,
        }).then(function mySuccess(data) {
            console.log(data['data']['data']); 
            if(data['data']['error'] == 0){
                window.location.href = cms;
            }else{
                alert(data['data']['warning']);
            }
     
        }, function myError(error) {
            console.log(error);
        });
    }
});