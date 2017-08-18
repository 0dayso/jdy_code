angular.module('activityApp',['ngRoute'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}).controller('projectCtrl',function ($scope,$http) {
// 请求成功执行的代码
    $http.get('/activity/autumn/project').then(function successCallback(response) {
        // 请求成功执行的代码
        $scope.projectList= {};

        if( response.status == 200 && response.data !='' ){
            $scope.projectList  =response.data ;
        }
    })
}, function errorCallback(response) {
// 请求失败执行代码
    $scope.projectList= {}
    console.log('未能加载活动项目'+response)
});