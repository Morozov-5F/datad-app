const app = angular.module('app');

app.controller('ServiceDetailsController', function ($scope, $stateParams, Providers) {
    $scope.loading = true;
    $scope.cropValue = (val) => {
        let res = '';
        if (val >= 1e+6)
        {
            res = Math.round(Math.floor(val / 1e+5)) / 10 + 'M';
        }
        else if (val >= 1e+3)
        {
            res = Math.round(val / 1e+2) / 10 + 'k';
        }
        else
        {
            res = Math.floor(val);
        }
        return res;
    };
    
    $scope.urls = {
        '1' : 'https://www.youtube.com/channel/',
        '2' : 'https://vk.com/'
    }

    Providers.getByID($stateParams.id)
        .then((result) => {
            if (result.data && result.data.users && result.data.users[0])
                $scope.profile = result.data.users[0];
            
            $scope.profile.url = $scope.urls[$scope.profile.socialID] + $scope.profile.profileID;

            $scope.profile.description = decodeURIComponent($scope.profile.description);
            if ($scope.profile.description.length > 100) 
                $scope.profile.description = $scope.profile.description.slice(0, 110) + '...';
            
        })
        .finally(() => $scope.loading = false );
    $scope.profile = {};

    $scope.openLink = (url) => {
        window.open(url, '_system', 'location=yes')
    }

    let socialNames = [
        'YouTube channel',
        'VK community',
        'Instagram profile'        
    ];
    $scope.getSocialName = (id) => socialNames[parseInt(id) - 1];
});