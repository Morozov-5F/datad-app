const app = angular.module('app');

app.controller('ServiceDetailsController', function ($scope, $stateParams, Providers) {
    $scope.loading = true;
    $scope.visibleDescription = '...';
    $scope.showAllDescription = true;

    $scope.labels = ['','','','','','',''];
    $scope.series = ['Series A', 'Series B'];
    $scope.data = [
        [65, 59, 80, 81, 56, 55, 40],
        [28, 48, 40, 19, 86, 27, 90]
    ];
    $scope.colors = ['#11c1f3', '#11c1f3', '#11c1f3'];
    $scope.options = {
        scales: {
            xAxes: [{
                display: false,
                gridLines: [{
                    display: true,
                    
                }]
            }],
            yAxes: [{
                display: false,
                gridLines: [{
                    display: true,
                }]
            }]
        }
    }
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
        '2' : 'https://vk.com/',
        '3' : 'https://www.instagram.com/'
    }

    $scope.images = [
        '',
        'youtube-colored',
        'vk-colored',
        'instagram-colored'
    ]

    Providers.getByID($stateParams.id)
        .then((result) => {
            if (result.data && result.data.users && result.data.users[0])
                $scope.profile = result.data.users[0];
            
            $scope.profile.url = $scope.urls[$scope.profile.socialID] + $scope.profile.profileID;
            if (!$scope.profile.avatar || $scope.profile.avatar === "") {
                $scope.profile.avatar = "assets/img/avatar-placeholder.png";
            }  
            if ($scope.profile.description && $scope.profile.description !== '') {
                $scope.profile.description = decodeURIComponent($scope.profile.description);
            } else {
                $scope.profile.description = 'No description';
            }
            $scope.toggleVisibleDescription();
        })
        .finally(() => $scope.loading = false );
    $scope.profile = {};

    $scope.toggleVisibleDescription = () => {
        $scope.showAllDescription = !$scope.showAllDescription;

        
        if ($scope.showAllDescription)
            $scope.visibleDescription = $scope.profile.description;
        else {
            $scope.visibleDescription = $scope.profile.description.length > 75 ? $scope.profile.description.slice(0, 75) + '...' : $scope.profile.description;
        }
    };

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