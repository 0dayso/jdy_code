    var activityApp = angular.module('activityApp',[], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    }).controller('summationCtrl',function ($scope,$http) {
        $http.get('/thirdAnniversary/summation').then(function successCallback(response) {
            // 请求成功执行的代码
            $scope.summation    =   response.data.summation;
            $scope.percentage   =   'step'+response.data.percentage;
            $scope.diffDay      =   response.data.diffDay;
        }, function errorCallback(response) {
            // 请求失败执行代码
            $scope.summation    =   '43,213,222.00';
            $scope.percentage   =   'step1';
            $scope.diffDay      =   20;
        });
    }).controller('recordCtrl',function ($scope,$http) {
        $http.get('/thirdAnniversary/souvenir').then(function successCallback(response) {
            // 请求成功执行的代码
            $scope.recordList   =   {};
            $scope.recordNumber =   {};
            if( response.data.record.lotteryNum >0 ) {
                $scope.recordList   =   response.data.record.list;
                $scope.recordNumber =   response.data.record.lotteryNum;
            };
        });
    }).controller('projectCtrl',function ($scope,$http) {
        $http.get('/thirdAnniversary/showProject').then(function successCallback(response) {
            // 请求成功执行的代码
            $scope.projectList= {};
            if( response.status == 200 && response.data !='' ){
                $scope.projectList  =response.data ;
            }
        })
    }).controller('userCtrl',function ($scope,$http) {
        $http.get('/thirdAnniversary/userLevel').then(function successCallback(response) {
            // 请求成功执行的代码
            $scope.userStatus=  false;
            $scope.userLevel =  '';
            $scope.account   =  '0.00';
            $scope.grade     =  {};
            $scope.lotteryNum=  0;
            $scope.status    =  false;
            $scope.min_invest=  20000;
            $scope.grade_money= 20000;
            $scope.levelNote  = '';
            if( response.status == 200 && response.data.grade ){
                $scope.userStatus=  response.data.userStatus;
                $scope.userLevel =  response.data.grade.grade_level;
                $scope.account   =  response.data.account;
                $scope.grade     =  response.data.grade;
                $scope.status    =  response.data.status;
                $scope.lotteryNum=  response.data.number;
                $scope.grade_money= response.data.grade.grade_money;
            }
            if( $scope.grade.grade_level ) {
                levellist = new Array()
                for( var i=0;i< $scope.grade.grade_level; i++ ){
                  levellist[i] = 'L'+(i+1);
                }
                $scope.levelNote  = levellist.join('、')
            }
            $scope.setBlock = function (status) {
                var block = "none";
                if (status == true) {
                    block = 'block';
                }
                return {"display": block};
            };
        })
    });

