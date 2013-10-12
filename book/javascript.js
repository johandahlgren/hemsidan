var posX 			= 0;
var posY 			= 0;
var divId 			= "";
var listParameters	= "";

$(document).click(function(e) {
	posX = e.pageX;
	posY = e.pageY;
});
function countWords(aTextFieldId, aOutputFieldId)
{
	var textString 	= document.getElementById(aTextFieldId).value;
	var textArray 	= textString.split(" ")
	document.getElementById(aOutputFieldId).value = textArray.length;
}
function initializeMap(aGLatLng, aZoom) 
{
	if (GBrowserIsCompatible()) 
	{
		var map = new GMap2(document.getElementById("locationMap"));
		map.addControl(new GLargeMapControl());
		map.setCenter(aGLatLng, aZoom);
	}
	return map;
}
function setMapMarker(aMap, aGLatLng, aTitle, aHref)
{
	var mapMarker 		= new GMarker(aGLatLng);
	mapMarker.title 	= aTitle;
	mapMarker.clickable = true;
	
	GEvent.addListener(mapMarker, "click", function() {
		goTo(aHref);
    }) 

	aMap.addOverlay(mapMarker);
}
function drawLine(aMap, aStart, aEnd)
{
	var polyline = new GPolyline([aStart, aEnd], "#ff0000", 3);
	aMap.addOverlay(polyline);
}

function connectContent(aListType, aTitle, aType, aItemId, aBookId, aConnect, aConnectTo, aIdentifier, aDivId) 
{
	document.inputForm.elements["user_action"].value 			= 'connect'; 
	document.inputForm.elements["connect"].value 				= aConnect; 
	document.inputForm.elements["connect_to"].value 			= aConnectTo; 
	document.inputForm.elements["connection_description"].value = document.getElementById("connectionDescription" + aIdentifier).value; 
	document.inputForm.elements["list_type"].value 				= aListType;
	//document.inputForm.submit();
	
	listParameters = "user_action=display_list&list_type=" + aListType + "&title=" + aTitle + "&type=" + aType + "&item_id=" + aItemId + "&book_id=" + aBookId + "&div_id=" + aDivId;
		
	divId 			= aDivId;
	
	$("#inputForm").ajaxSubmit(setupFormOptions());
}
function removeConnection(aListType, aTitle, aType, aItemId, aBookId, aContentId1, aContentId2, aDivId)
{
	if (confirm("Do you want to remove this connection?"))
	{
		document.inputForm.elements["user_action"].value 	= "remove_connection";
		document.inputForm.elements["content_id_1"].value 	= aContentId1; 
		document.inputForm.elements["content_id_2"].value 	= aContentId2;
		document.inputForm.elements["list_type"].value 		= aListType;
		//document.inputForm.submit();
		
		listParameters = "user_action=display_list&list_type=" + aListType + "&title=" + aTitle + "&type=" + aType + "&item_id=" + aItemId + "&book_id=" + aBookId + "&div_id=" + aDivId;
		
		divId 			= aDivId;
		
		$("#inputForm").ajaxSubmit(setupFormOptions());
	}
}
function deleteItem(aItemId, aDivId, aListParameters)
{		
	if (confirm("Do you really want to delete this item?"))
	{
		document.inputForm.elements["user_action"].value 	= "delete";
		document.inputForm.elements["item_id"].value 		= aItemId;
		
		$("#inputForm").ajaxSubmit(setupFormOptions()); 
		
		loadAjaxData(aDivId, aListParameters);
	}
}
function moveItem(aItemId, aDirection, aDivId, aListParameters) 
{
	document.inputForm.elements["user_action"].value 		= "move_item";
	document.inputForm.elements["item_id"].value 			= aItemId;
	document.inputForm.elements["direction"].value 			= aDirection;
	returnData = ajaxSave();
	handleRespones(returnData);
}
function createNewUser()
{
	var userId 		= document.inputForm.elements["new_user_id"].value;
	var password	= document.inputForm.elements["new_password"].value;
	
	if (userId == null || password == null)
	{
		alert("Please enter both a user ID and a password!");
	}
	else
	{
		document.inputForm.elements["user_action"].value = "new_user";
		document.inputForm.action = "index.php";
		document.inputForm.submit();
	}
}

function displaySynonyms()
{
	var word 		= document.getElementById("synonym").value;
	var dialogUrl 	= "http://vancouver-webpages.com/cgi-bin/find-synonyms.cgi?name=" + word;

	var $myDialog = $("<div id=\"dialogContainer\" class=\"dropshadow\"><iframe id=\"dialogContent\" src=\"" + dialogUrl + "\" /></div>").dialog({
		width: 500,
		height: 300,
		buttons: { "Ok": function() { $(this).dialog("close"); } }
	});
}
function updateTabs(aTabIndex, aBookId)
{
	parent.$("#tabsContainer").tabs().tabs("select", aTabIndex);
}
function showHideDiv(aDivId)
{
	$("#" + aDivId).toggle();
}
function positionDivAtPointer(aDivId)
{
	var height 			= $("#" + aDivId).height();
	var width 			= $("#" + aDivId).width();
	leftVal 			= posX - (width/2);
	topVal 				= posY - (height/2);
	var windowHeight	= $(window).height();
	var windowWidth		= $(window).width();

	if (topVal < 0)
	{
		topVal = 0;
	}
	else if (topVal + height > windowHeight)
	{
		topVal = windowHeight - height;
	}

	$("#" + aDivId).css({left: leftVal + "px", top: topVal + "px"});
}
function showTimeLineDiv(aType, aCharacterId, aNewSortOrder, aReturnUrl)
{
	document.inputForm.elements["type"].value 				= aType;
	document.inputForm.elements["parent_id"].value 			= aCharacterId;
	document.inputForm.elements["data_sort_order"].value 	= aNewSortOrder;
	document.inputForm.elements["return_url"].value 		= aReturnUrl;
	positionDivAtPointer("timelineDiv");
	$("#insertBeforeSaveButton").hide(); 
	$("#updateItemSaveButton").show();
	showHideDiv("timelineDiv");
	$("#dataText").focus();
}
function addItemBeforeThisOne(aType, aNewSortOrder, aParentId)
{
	document.inputForm.elements["user_action"].value 		= "save";
	document.inputForm.elements["type"].value 				= aType;
	document.inputForm.elements["parent_id"].value 			= aParentId;
	document.inputForm.elements["data_sort_order"].value 	= aNewSortOrder;
	document.inputForm.elements["data_text"].value 			= "";
	positionDivAtPointer("timelineDiv");
	$("#insertBeforeSaveButton").show(); 
	$("#updateItemSaveButton").hide();
	showHideDiv("timelineDiv"); 
}
function editTimelineItem(aId, aText, aSortOrder, aRowSpan)
{
	document.inputForm.elements["user_action"].value 		= "save";
	document.inputForm.elements["item_id"].value 			= aId;
	document.inputForm.elements["data_text"].value 			= aText;
	document.inputForm.elements["data_sort_order"].value 	= aSortOrder;
	document.inputForm.elements["data_row_span"].value 		= aRowSpan;
	positionDivAtPointer("timelineDiv");
	$("#insertBeforeSaveButton").hide();
	$("#updateItemSaveButton").show();
	showHideDiv("timelineDiv"); 
} 
function setRowSpan(aId, aRowSpan, aText, aSortOrder, aReturnUrl) 
{
	document.inputForm.elements["user_action"].value 		= "save";
	document.inputForm.elements["item_id"].value 			= aId;
	document.inputForm.elements["data_text"].value 			= aText;
	document.inputForm.elements["data_sort_order"].value 	= aSortOrder;
	document.inputForm.elements["data_row_span"].value 		= aRowSpan;
	document.inputForm.elements["return_url"].value 		= aReturnUrl;
	//document.inputForm.submit();
	
	$("#inputForm").ajaxSubmit(setupFormOptions());
}

function displayBackgroundColour(aColour)
{
	$("#statusMarker").css("background-color", aColour);
	$("#statusMarker").fadeIn(100, function(){
		hideAjaxStatusDiv()
	});
}

function displaySuccessMessage(responseText, statusText, xhr, $form)
{
	displayBackgroundColour("rgb(0, 255, 0)");
	loadAjaxData(divId, listParameters);
}
function displayFailureMessage(responseText, statusText, xhr, $form)
{
	displayBackgroundColour("rgb(255, 0, 0)");
	alert("Error: " + responseText + " - " + statusText);
}

function displayAjaxStatusDiv()
{
	$("#messageDiv").fadeIn(100); 
}
function hideAjaxStatusDiv()
{
	$("#messageDiv").delay(500).fadeOut(100, function() {
		$("#statusMarker").css("background-color", "rgb(127, 127, 127)");
	});
}

function setupFormOptions()
{
	var options = { 
		target:        "#statusMarker",   		// target element(s) to be updated with server response 
		beforeSubmit:  	displayAjaxStatusDiv,  	// pre-submit callback 
		success:   		displaySuccessMessage,  // post-submit callback
		error:       	displayFailureMessage,  // post-submit callback  
 
		// other available options: 
		//url:       url         // override for form's 'action' attribute 
		//type:      type        // 'get' or 'post', override for form's 'method' attribute 
		//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
		//clearForm: true        // clear all form fields after successful submit 
		//resetForm: true        // reset the form after successful submit 
  
		// $.ajax options can be used here too, for example: 
		timeout:   5000,
		contentType: "application/x-www-form-urlencoded;charset=UTF-8"
	}; 
	return options;
}

function setupForm()
{
	$("#inputForm").submit(function() { 
		$(this).ajaxSubmit(setupFormOptions()); 
		return false; 
	});
}

function setupDelete()
{
	if (confirm("Are you sure you want to delete this item?"))
	{
		document.inputForm.elements["user_action"].value 	= "delete";
	}
}

function loadAjaxData(aTargetDivId, aParameters)
{
	$.ajax({
		url: "ajaxService.php",
		type: "POST",
		data: aParameters,
		dataType: "html",
		success: function(data, textStatus, XMLHttpRequest) {
			$("#" + aTargetDivId).html(data);
		},
		error: function(msg)
		{
			alert("Error: " + msg);
		}
	})
}

function ajaxSave()
{
	$.ajax({
		url: "ajaxService.php",
		type: "POST",
		data: $("#inputForm").serialize(),
		dataType: "json",
		timeout:   5000,
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		beforeSend: function() {
			displayAjaxStatusDiv();
		},
		success: function(data, textStatus, XMLHttpRequest) {
			if (data.response.code == 0)
			{
				handleResponse(data);
				
				$("#statusMarker").html(data.response.message);
				displayBackgroundColour("rgb(0, 255, 0)");
			}
			else
			{
				$("#statusMarker").html(data.response.message);
				displayBackgroundColour("rgb(255, 0, 0)");
			}
		},
		error: function(msg)
		{
			$("#statusMarker").html(msg);
			displayBackgroundColour("rgb(255, 0, 0)");
		}
	})
}

function handleResponse (aData)
{
	var type 		= document.inputForm.elements["type"].value;
	var title 		= document.inputForm.elements["title"].value;
	var bookId 		= document.inputForm.elements["book_id"].value;
	var parentId 	= document.inputForm.elements["parent_id"].value;
	var itemId 		= document.inputForm.elements["item_id"].value;
	
	if (document.inputForm.elements["user_action"].value == "new")
	{
		var locationString 	= "index.php?type=" + type + "&book_id=" + bookId + "&parent_id=" + parentId + "&item_id=" + aData.response.itemId;
		goTo(locationString);
	}
	else if(document.inputForm.elements["user_action"].value == "save" || document.inputForm.elements["user_action"].value == "move_item")
	{
		var paramsString = "user_action=display_list&list_type=menu&title=" + title  + "&type=" + type + "&item_id=" + bookId + "&book_id=" + bookId + "&parent_id=" + parentId;
		loadAjaxData("leftMenuDiv", paramsString);
	}
	else if(document.inputForm.elements["user_action"].value == "delete")
	{
		var locationString 	= "index.php?type=" + type + "&book_id=" + bookId + "&parent_id=" + parentId;
		goTo(locationString);
	}
}

function updateImage(aImageUrl, aImageId)
{
	$("#" + aImageId).attr("src", aImageUrl);
	$("#" + aImageId).attr("class", "portrait");
}

function goTo(aUrl)
{
	document.location = aUrl;
}

function cancelBubbling(e)
{
	if(!e) e = window.event;
	e.cancelBubble = true;
	if(e.stopPropagation) e.stopPropagation();
}