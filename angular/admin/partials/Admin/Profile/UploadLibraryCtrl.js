angular.module(appName).controller('UploadLibraryCtrl', function ($scope, $rootScope, $state, $timeout, Extention , Upload) {

	$scope.allTags = [];
	// uploading -> 0
	// uploaded -> 1
	// upload_error -> 2

	Extention.post('getAllTags').then(function (res) {
		$scope.allTags = res.Items;
	});
	$scope.isResumeSupported = Upload.isResumeSupported();

	Extention.post('getUploadLibraryData').then(function (res) {
		$scope.pageData = res;
	});

	$scope.subjectChanged = function (file) {

		file.allChildSubjects = undefined;
		file.Subject = undefined;
		$timeout(function () {
			file.allChildSubjects = file.MainSubject.Childs;
		});
	};

	$scope.togglePauseUploadFile = function (file) {
		file.uploader.pause();
	};

	$scope.removeFile = function (file) {
		if(file.uploadState == 0){
			file.uploader.abort();
			file.percent = '0';
		}else{
			var index = $scope.myFiles.indexOf(file);
			$scope.myFiles.splice(index,1);
		}
	};
	
	$scope.startUploadAll = function () {

		if($scope.myFiles && $scope.myFiles.length){
			var fileCancel = 0;
			for (var i = 0; i < $scope.myFiles.length; i++) {
				var file = $scope.myFiles[i];

				if(file.uploadState == 1){
					fileCancel++;
					continue;
				}

				$scope.startUploadFile(file);
			}

			if(fileCancel == $scope.myFiles.length){
				Extention.popInfo('تمامی فایل ها آپلود شده اند!');
			}
		}else{
			Extention.popInfo('هیچ فایلی برای آپلود انتخاب نشده!');
		}

	}

	$scope.startUploadFile = function (file) {

		if(file.uploadState == 0)
			return;

		file.uploadState = 0;

		var subjectID = undefined;
		var mainSubjectID = undefined;

		if(angular.isDefined(file.MainSubject)){
			mainSubjectID = file.MainSubject.SubjectID;
		}

		if(angular.isDefined(file.Subject)){
			subjectID = file.Subject.ID;
		}
console.log({file: file , Description : file.Description ,
	SubjectID : subjectID, MainSubjectID :mainSubjectID,
	Title : file.Title , Tags : file.Tags
});
		file.uploader = Upload.upload({
			url: uploadURL + 'upload_library.php',
			data: {file: file , Description : file.Description ,
				SubjectID : subjectID, MainSubjectID :mainSubjectID,
				Title : file.Title , Tags : file.Tags
			}
		});

		file.uploader.then(function (resp) {
			resp.config.data.file.uploadState = 1;
			console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
		}, function (resp) {
			resp.config.data.file.uploadState = 2;
			if(resp.status == -1)
				resp.config.data.file.percent = 0;
			console.log('Error status: ' + resp.status);
		}, function (evt) {
			var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
			evt.config.data.file.percent = progressPercentage;
			evt.config.data.file.loaded = $scope.sizeFilter(evt.loaded);
			//console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
		});
	}

	var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PT'];

	$scope.sizeFilter = function(bytes) {

		var number = Math.floor(Math.log(bytes) / Math.log(1024));
		return persianJs((bytes / Math.pow(1024, Math.floor(number))).toFixed(1)).englishNumber().toString() +
			' ' + units[number] ;
	}

	$scope.abortUpload = function (file) {
		file.uploader.abort();
	}

	$scope.isImageFormat = function (type) {
		var i = type.indexOf('image');
		return i > -1;
	}

	activeElement('#SLibrary', '#SUpload');
});