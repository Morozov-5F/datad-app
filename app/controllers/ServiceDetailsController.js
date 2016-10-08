const app = angular.module('app');

app.controller('ServiceDetailsController', function ($scope, $stateParams, Providers) {
    Providers.getByID($stateParams.id)
        .then((result) => {
            console.log(result.data.users[0]);
            if (result.data && result.data.users && result.data.users[0])
                $scope.profile = result.data.users[0];
        });
    $scope.profile = {};

    let socialNames = [
        'YouTube channel',
        'VK community',
        'Instagram profile'        
    ];
    $scope.getSocialName = (id) => socialNames[parseInt(id) - 1];
});