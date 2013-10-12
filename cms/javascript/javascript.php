var wysiwygEditorIds = new Array();

function loadAjaxData(aTargetDivId, aParameters)
{
	$.ajax({
		url: "cms/ajaxHandler.php",
		type: "POST",
		data: aParameters,
		dataType: "html",
		timeout:   5000,
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		beforeSend: function() {
			initAjaxLoad();
		},
		success: function(data, textStatus, XMLHttpRequest) 
		{
			$("#" + aTargetDivId).html(data);
			cleanUpAjaxLoad();
		},
		error: function(msg)
		{
			handleError(msg);
			cleanUpAjaxLoad();
		}
	})
}

function ajaxSave()
{
	for (i = 0; i < wysiwygEditorIds.length; i++)
	{
		var wysiwyg = nicEditors.findEditor(wysiwygEditorIds[i]);
		wysiwyg.saveContent();
	}
	
	$.ajax({
		url: "cms/ajaxHandler.php",
		type: "POST",
		data: $("#inputForm").serialize(),
		dataType: "json",
		timeout:   5000,
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		beforeSend: function() {
			//alert($("#inputForm").serialize());
			displayAjaxLoadingAnimation();
			initAjaxLoad();
		},
		success: function(response, textStatus, XMLHttpRequest) 
		{
			if (response == null)
			{
				handleError("Odefinierat fel. Response var null.");
			}
			else if (response.response.code == 0)
			{
				handleSuccess(response);
				var originalDivId = $("#originatingDivId").val();
				originalDiv = $("#" + originalDivId);
				$(originalDiv).trigger("update");
				closeAdmin();
			}
			else
			{
				handleError(response.response.message);
			}
			cleanUpAjaxLoad();
		},
		error: function(msg)
		{
			handleError(msg.responseText);
			cleanUpAjaxLoad();
		}
	})
}

function ajaxDelete(aEntityId, aDivId)
{
	if (confirm("Är du säker?"))
	{
		$.ajax({
			url: "cms/ajaxHandler.php",
			type: "POST",
			data: "userAction=delete&entityId=" + aEntityId,
			dataType: "json",
			timeout:   5000,
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			beforeSend: function() {
				initAjaxLoad();	
			},
			success: function(response, textStatus, XMLHttpRequest) 
			{
				if (response == null)
				{
					handleError("Odefinierat fel. Response var null.");
				}
				else if (response.response.code == 0)
				{
					//displaySuccessMessage(response.response.message);
					$("#" + aDivId).trigger("update");
				}
				else
				{
					handleError(response.response.message);
				}
				cleanUpAjaxLoad();
			},
			error: function(msg)
			{
				handleError(msg);
				cleanUpAjaxLoad();
			}
		})
	}
}

function initAjaxLoad()
{
	$("body").css("cursor", "wait");
	hideDiv("messageDiv");
}

function cleanUpAjaxLoad()
{
	$("body").css("cursor", "auto");
}

function handleSuccess(aResponse)
{	
	if ($("#userAction").val() == "insert")
	{
		$("#userAction").val("update");
		$("#entityId").val(aResponse.response.entityId);
	}
	
	hideAjaxLoadingAnimation();
	$("#resultDiv").css("background-color", "rgb(0, 255, 0)");
	$("#resultDiv").fadeIn(50);
	$("#resultDiv").delay(800).fadeOut(300);
}

function displaySuccessMessage(aMessage)
{	
	hideAjaxLoadingAnimation();
	
	$("#messageDiv").html(aMessage);
	$("#messageDiv").fadeIn(50);
}

function handleError(aMessage)
{	
	hideAjaxLoadingAnimation();
	$("#resultDiv").css("background-color", "rgb(255, 0, 0)");
	$("#resultDiv").fadeIn(100);
	$("#resultDiv").delay(800).fadeOut(300);
	$("#messageDiv").html(aMessage);
	$("#messageDiv").fadeIn(50);
}

function displayAjaxLoadingAnimation()
{
	//$("#loadingLayer").fadeIn(500);
}

function hideAjaxLoadingAnimation()
{
	//$("#loadingLayer").css("display", "none");
}

function goTo(aUrl)
{
	document.location = aUrl;
}

function hideDiv(aDivId)
{
	$("#" + aDivId).fadeOut(100);
}

function openAdmin(aUrl, aAdminDiv, aDivToRefresh, aEditInline)
{
	aUrl = aUrl + "&browserWindowWidth=" + document.documentElement.clientWidth;
	
	$.ajax({
		url: aUrl,
		type: "POST",
		data: "",
		dataType: "html",
		timeout:   5000,
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		beforeSend: function() {
			initAjaxLoad();
			$(".buttonNew").hide();
			$(".entityAdminButtons").hide();
		},
		success: function(data, textStatus, XMLHttpRequest) {
			if (aEditInline)
			{
				$("#" + aAdminDiv).children().css("display", "none");
			}
			else
			{
				$("#" + aAdminDiv).children().remove();
			}
			$("#" + aAdminDiv).append(data);
			$("#" + aAdminDiv).css("display", "block");
			$("#adminDivId").val(aAdminDiv);
			$("#originatingDivId").val(aDivToRefresh);
			$("#editInline").val(aEditInline);
			cleanUpAjaxLoad();
			focusFirstField();
		},
		error: function(msg)
		{
			handleError(msg);
			cleanUpAjaxLoad();
		}
	})
}

function closeAdmin()
{
	var adminDivId = $("#adminDivId").val();
	var editInline = $("#editInline").val();

	$("#editDiv").remove();
								
	if (editInline)
	{
		$("#" + adminDivId).children().css("display", "block");
	}
	else
	{
		$("#" + adminDivId).css("display", "none");
	}
	$(".openAdminButton").show();
	$(".buttonNew").show();
	$(".entityAdminButtons").show();
}

function displayAdminPanel(aId)
{
	$(".openAdminButton").hide();
	$(".buttonNew").hide();
	$("#buttons_" + aId).show();
}

function hideAdminPanel(aId)
{
	$(".openAdminButton").show();
	$(".buttonNew").show();
	$("#buttons_" + aId).hide();
}

function focusFirstField()
{
	var fields = $("input");
	
	for (i = 0; i < fields.length; i++)
	{
		if (fields[i].type != "hidden")
		{
			fields[i].focus();
			break;
		}
	}
}

function showHideAdminButtons()
{
		$(".entityAdminButtons, .newButtonContainer").toggle();
}

function dumpProps(obj, parent) 
{
   // Go through all the properties of the passed-in object 
   for (var i in obj) {
      // if a parent (2nd parameter) was passed in, then use that to 
      // build the message. Message includes i (the object's property name) 
      // then the object's property value on a new line 
      if (parent) { var msg = parent + "." + i + "\n" + obj[i]; } else { var msg = i + "\n" + obj[i]; }
      // Display the message. If the user clicks "OK", then continue. If they 
      // click "CANCEL" then quit this level of recursion 
      if (!confirm(msg)) { return; }
      // If this property (i) is an object, then recursively process the object 
      if (typeof obj[i] == "object") { 
         if (parent) { dumpProps(obj[i], parent + "." + i); } else { dumpProps(obj[i], i); }
      }
   }
}

function addWysiwyg(aDivId)
{
	wysiwygEditorIds.push(aDivId); 
	new nicEditor({uploadURI: "cms/nicUpload.php", iconsPath: "cms/style/images/nicEditorIcons.gif", buttonList: ['bold','italic','underline','foreColor','left','center','right','justify','link','unlink','image','upload']}).panelInstance(aDivId);
}

