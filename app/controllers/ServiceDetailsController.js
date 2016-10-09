const app = angular.module('app');

app.controller('ServiceDetailsController', function ($scope, $stateParams, Providers) {
    Providers.getByID($stateParams.id)
        .then((result) => {
            if (result.data && result.data.users && result.data.users[0])
                $scope.profile = result.data.users[0];

            $scope.profile.description = decodeURIComponent($scope.profile.description);
            if ($scope.profile.description.length > 100) 
                $scope.profile.description = $scope.profile.description.slice(0, 110) + '...';
        });
    $scope.profile = {};

    let socialNames = [
        'YouTube channel',
        'VK community',
        'Instagram profile'        
    ];
    $scope.getSocialName = (id) => socialNames[parseInt(id) - 1];
});