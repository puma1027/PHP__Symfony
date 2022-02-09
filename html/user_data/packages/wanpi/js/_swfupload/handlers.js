function preLoad() {
	if (!this.support.loading) {
		//alert("You need the Flash Player 9.028 or above to use SWFUpload.");
		alert("ファイルをアップロードするにはFlash Player9.028以上が必要です。");
		return false;
	}
}
function loadFailed() {
	//alert("Something went wrong while loading SWFUpload. If this were a real application we'd clean up and then give you an alternative");
	alert("エラーはアップロードで発生しました。");
}
function swfUploadLoaded() {

}

function fileDialogStart() {
	var txtFileName = document.getElementById(this.customSettings.upload_file_text);
	var fileHidden = document.getElementById(this.customSettings.uploaded_file_hid);
	var aFile = document.getElementById(this.customSettings.uploaded_file_a);
	
	txtFileName.value = "";
	fileHidden.value = "";
	aFile.innerHTML = "";
	
	this.cancelUpload();
}


function fileQueueError(file, errorCode, message)  {
	try {
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			//alert("The file you selected is too big.");
			alert("選択したファイルが大きすぎます。");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			return;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			//alert("The file you selected is empty.  Please select another file.");
			alert("あなたが選択したファイルは空です。別のファイルを選択してください。");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			return;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			//alert("The file you choose is not an allowed file type.");
			alert("選択したファイルには、許可されたファイルタイプではありません。");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			return;
		default:
			//alert("An error occurred in the upload. Try again later.");
			alert("エラーはアップロードで発生しました。後でもう一度やり直してください。");
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			return;
		}
	} catch (e) {
	}
}

function fileQueued(file) {
	try {
		var txtFileName = document.getElementById(this.customSettings.upload_file_text);
		txtFileName.value = file.name;
		
	} catch (e) {
		this.debug(e);
	}

}

//Dialog off
function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		this.startUpload();
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		//file.id = "singlefile1";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(file, this.customSettings.progress_target);
		progress.setProgress(percent);
		progress.setStatus("アップロード中...");
		progress.toggleCancel(true, this);
	} catch (e) {
	}
}


function uploadSuccess(file, serverData) {
	try {
		//file.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(file, this.customSettings.progress_target);
		progress.setComplete();
		progress.setStatus("");
		progress.toggleCancel(true);

		if (serverData === "") {
			this.customSettings.upload_successful = false;
		} else {
			this.customSettings.upload_successful = true;
			arrServerData = serverData.split(";");
			
			hid = document.getElementById(this.customSettings.uploaded_file_hid);
			hid.value = arrServerData[0];
			hid_full = document.getElementById(this.customSettings.uploaded_file_full_hid);
			hid_full.value = arrServerData[1];
			
			a = document.getElementById(this.customSettings.uploaded_file_a);
			if(this.customSettings.uploaded_text){
				a.innerHTML = this.customSettings.uploaded_text;
			}else{
				a.innerHTML = "添付ファイル";
			}
			a.href = arrServerData[2]+arrServerData[0];
		}
		
	} catch (e) {
	}
}

function uploadComplete(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progress_target);
		if (this.customSettings.upload_successful) {

		} else {
			progress.setError();
			progress.setStatus("ファイルが拒否されました。");
			progress.toggleCancel(true);
			
			var txtFileName = document.getElementById("txtFileName");
			txtFileName.value = "";

			//alert("There was a problem with the upload.\nThe server did not accept it.");
			alert("アップロードに問題がありました。\nサーバはそれを受け入れませんでした。");
		}
	} catch (e) {
	}
}

function uploadError(file, errorCode, message) {
	try {
		
		if (errorCode === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
			// Don't show cancelled error boxes
			return;
		}
		
		var txtFileName = document.getElementById("txtFileName");
		txtFileName.value = "";
		//validateForm();
		
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
			//alert("There was a configuration error.  You will not be able to upload a resume at this time.");
			alert("コンフィギュレーションエラーが発生しました。この時点で履歴書をアップロードすることができなくなります。");
			this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
			return;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			//alert("You may only upload 1 file.");
			alert("1のファイルだけアップロードできます");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			return;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			break;
		default:
			//alert("An error occurred in the upload. Try again later.");
			alert("エラーはアップロードで発生しました。後でもう一度やり直してください。");
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			return;
		}

		//file.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(file, this.customSettings.progress_target);
		progress.setError();
		progress.toggleCancel(true);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("アップロードエラー");
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("アップロードに失敗しました。");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("サーバ（IO）エラー");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("セキュリティエラー");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			progress.setStatus("アップロードキャンセル");
			this.debug("Error Code: Upload Cancelled, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("アップロード停止");
			this.debug("Error Code: Upload Stopped, File name: " + file.name + ", Message: " + message);
			break;
		}
	} catch (ex) {
	}
}
