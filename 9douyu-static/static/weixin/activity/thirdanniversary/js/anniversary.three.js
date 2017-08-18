angular.module('activityApp',['ngRoute'], function($interpolateProvider) {
            $interpolateProvider.startSymbol('<%');
            $interpolateProvider.endSymbol('%>');
        }).controller('summationCtrl',function ($scope,$http) {
            $http.get('/thirdAnniversary/summation').then(function successCallback(response) {
                $scope.summation    =   response.data.summation;
                $scope.percentage   =   'step'+response.data.percentage;
                $scope.diffDay      =   response.data.diffDay;
            }, function errorCallback(response) {
                $scope.summation    =   '43,213,222.00';
                $scope.percentage   =   'step1';
                $scope.diffDay      =   20;
            });
        }).controller('recordCtrl',function ($scope,$http) {
            $http.get('/thirdAnniversary/souvenir').then(function successCallback(response) {
                $scope.recordList   =   {};
                $scope.recordNumber =   {};
                if( response.data.record.lotteryNum >0 ) {
                    $scope.recordList   =   response.data.record.list;
                    $scope.recordNumber =   response.data.record.lotteryNum;
                };
            });
        }).controller('projectCtrl',function ($scope,$http) {
            $http.get('/thirdAnniversary/showProject').then(function successCallback(response) {
                $scope.projectList= {};
                if( response.status == 200 && response.data !='' ){
                    $scope.projectList  =response.data ;
                }
            })
        }).controller('prizeCtrl',function ($scope,$http) {
            $http.get('/thirdAnniversary/triplePrize').then(function successCallback(response) {
                $scope.prizeInfo  = {};
                $scope.JnhRecordList= {};
                if( response.status == 200 && response.data !='' ){
                    $scope.prizeInfo  =response.data.lottery ;
                    $scope.prizeInfo.imgUrl  =$("#jia-nian-hua-module").attr('attr-images-static')+'/static/weixin/activity/thirdanniversary/images/three/page-today-gift' + response.data.lottery.order_num +'.png' ;
                    $scope.JnhRecordList  =response.data.record.list ;
                }
            })
        }).controller('RainCtrl',function ($scope,$http) {
            $http.get('/thirdAnniversary/bonusStatus').then(function successCallback(response) {
                $scope.rain     =   {};
                if( response.status == 200 && response.data !='' ) {
                    $scope.rain.status  =   response.data.status;
                    $scope.rain.error   =   response.data.msg;
                    $scope.rain.type  =   response.data.data.type;
                }
            })
        })
